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

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ==============================
             | DATA KARYAWAN
             ============================== */

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
                ->required(),

            TextInput::make('company_name')
                ->label('Perusahaan')
                ->readonly()
                ->required(),

            TextInput::make('position_name')
                ->label('Posisi')
                ->readonly()
                ->required(),

            /* ==============================
             | PRESENSI MASUK
             ============================== */

            Section::make('Presensi Waktu Masuk')
                ->schema([
                    DatePicker::make('presence_in_date')
                        ->label('Tanggal Masuk')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),

                    TimePicker::make('presence_in_time')
                        ->label('Jam Masuk')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                ]),

            /* ==============================
             | PRESENSI PULANG
             ============================== */

            Section::make('Presensi Waktu Pulang')
                ->schema([
                    DatePicker::make('presence_out_date')
                        ->label('Tanggal Pulang')
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),

                    TimePicker::make('presence_out_time')
                        ->label('Jam Pulang')
                        ->nullable()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                ]),

            TextInput::make('total_time')
                ->label('Total Waktu (Menit)')
                ->numeric()
                ->readonly(),
        ]);
    }

    protected static function generateTotalMinute(Get $get, Set $set): void
    {
        $inDate  = $get('presence_in_date');
        $inTime  = $get('presence_in_time');
        $outDate = $get('presence_out_date');
        $outTime = $get('presence_out_time');

        if (! $inDate || ! $inTime || ! $outDate || ! $outTime) {
            return;
        }

        $start = Carbon::parse("$inDate $inTime");
        $end   = Carbon::parse("$outDate $outTime");

        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $set('total_time', $start->diffInMinutes($end));
    }
}
