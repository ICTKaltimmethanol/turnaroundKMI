<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('total_time')
                ->label('Total Waktu (Menit)')
                ->numeric()
                ->dehydrated(),

            Select::make('employee_code')
                ->label('ID Pekerja')
                ->options(
                    \App\Models\Employee::orderBy('employees_code')
                        ->pluck('employees_code', 'employees_code')
                )
                ->searchable()
                ->live()
                ->required()
                ->afterStateUpdated(function ($state, Set $set) {
                    $employee = \App\Models\Employee::with(['company', 'position'])
                        ->where('employees_code', $state)
                        ->first();

                    if (! $employee) {
                        return;
                    }

                    // FK
                    $set('employees_id', $employee->id);
                    $set('employees_company_id', $employee->company?->id);
                    $set('employees_position_id', $employee->position?->id);

                    // SNAPSHOT
                    $set('employee_name', $employee->full_name);
                    $set('company_name', $employee->company?->name);
                    $set('position_name', $employee->position?->name);
                }),

            Hidden::make('employees_id')->required(),
            Hidden::make('employees_company_id')->required(),
            Hidden::make('employees_position_id')->required(),

            TextInput::make('employee_name')
                ->label('Nama Pekerja')
                ->readonly()
                ->dehydrated(true)
                ->required(),

            TextInput::make('company_name')
                ->label('Perusahaan')
                ->readonly()
                ->dehydrated(true)
                ->required(),

            TextInput::make('position_name')
                ->label('Posisi')
                ->readonly()
                ->dehydrated(true)
                ->required(),

            Section::make('Presensi Waktu Masuk')
                ->relationship('presenceIn')
                ->schema([
                    Hidden::make('employees_id')
                        ->default(fn (Get $get) => $get('../employees_id'))
                        ->dehydrated(true)
                        ->required(),
                    DatePicker::make('presence_date')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                    TimePicker::make('presence_time')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                ]),

            Section::make('Presensi Waktu Pulang')
                ->relationship('presenceOut')
                ->schema([
                    Hidden::make('employees_id')
                        ->default(fn (Get $get) => $get('../employees_id'))
                        ->dehydrated(true)
                        ->required(),
                    DatePicker::make('presence_date')
                        ->label('Tanggal Pulang')
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                    TimePicker::make('presence_time')
                        ->label('Jam Pulang')
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                    Action::make('hapus_waktu_pulang')
                        ->label('Hapus Waktu Pulang')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (Get $get) => $get('presenceOut.presence_date') || $get('presenceOut.presence_time'))
                        ->action(function ($record, Set $set) {
                            $set('presenceOut.presence_date', null);
                            $set('presenceOut.presence_time', null);
                            $set('total_time', null);
                            $set('presenceOut_id', null);

                            if ($record) {
                                $record->update([
                                    'total_time' => null,
                                    'presenceOut_id' => null,
                                ]);
                                $record->presenceOut()?->delete();
                            }

                            Notification::make()
                                ->title('Berhasil')
                                ->body('Waktu pulang berhasil dihapus.')
                                ->success()
                                ->send();
                        }),
                ]),

        ])
        ->beforeSave(function ($form) {
            $data = $form->getState();

            // Buat presenceIn otomatis jika belum ada
            if (!isset($data['presenceIn_id'])) {
                $presenceIn = \App\Models\PresenceIn::create([
                    'employees_id' => $data['employees_id'],
                    'presence_date' => $data['presenceIn']['presence_date'] ?? now()->toDateString(),
                    'presence_time' => $data['presenceIn']['presence_time'] ?? now()->format('H:i'),
                ]);
                $form->model->presenceIn_id = $presenceIn->id;
            }

            // Buat presenceOut jika sudah diisi
            if (isset($data['presenceOut']['presence_date']) && isset($data['presenceOut']['presence_time'])) {
                $presenceOut = \App\Models\PresenceOut::create([
                    'employees_id' => $data['employees_id'],
                    'presence_date' => $data['presenceOut']['presence_date'],
                    'presence_time' => $data['presenceOut']['presence_time'],
                ]);
                $form->model->presenceOut_id = $presenceOut->id;
            }
        });
    }

    protected static function generateTotalMinute(Get $get, Set $set): void
    {
        $inDate  = $get('../presenceIn.presence_date');
        $inTime  = $get('../presenceIn.presence_time');
        $outDate = $get('../presenceOut.presence_date');
        $outTime = $get('../presenceOut.presence_time');

        if (! $inDate || ! $inTime || ! $outDate || ! $outTime) {
            return;
        }

        $start = Carbon::parse("$inDate $inTime");
        $end   = Carbon::parse("$outDate $outTime");

        // SHIFT MALAM
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $set('../total_time', $start->diffInMinutes($end));
    }
}
