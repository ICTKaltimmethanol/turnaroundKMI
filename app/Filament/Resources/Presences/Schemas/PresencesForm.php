<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions\Action;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;

use Filament\Notifications\Notification;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /* ======================
               TOTAL TIME
            ====================== */
            TextInput::make('total_time')
                ->label('Total Time')
                ->numeric()
                ->disabled(),

            /* ======================
               EMPLOYEE
            ====================== */
            Select::make('employees_id')
                ->label('Nama Karyawan')
                ->relationship('employee', 'full_name')
                ->disabled()
                ->searchable()
                ->preload(),

            Select::make('company_id')
                ->label('Perusahaan')
                ->relationship('company', 'name', modifyQueryUsing: fn ($q) => $q->orderBy('name'))
                ->searchable()
                ->preload()
                ->required(),

            Select::make('position_id')
                ->label('Posisi')
                ->relationship('position', 'name', modifyQueryUsing: fn ($q) => $q->orderBy('name'))
                ->searchable()
                ->preload()
                ->required(),

            /* ======================
               PRESENCE IN
            ====================== */
            Section::make('Presensi Waktu Masuk')
                ->schema([
                    DatePicker::make('presenceIn.presence_date')
                        ->label('Tanggal Masuk'),

                    TimePicker::make('presenceIn.presence_time')
                        ->label('Waktu Masuk'),

                    Action::make('removePresenceIn')
                        ->label('Remove')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => filled($record?->presenceIn))
                        ->action(function ($record, $set) {
                            $record->presenceIn?->delete();
                            $set('presenceIn_id', null);

                            Notification::make()
                                ->success()
                                ->title('Presensi Masuk dihapus')
                                ->send();
                        }),
                ]),

            /* ======================
               PRESENCE OUT
            ====================== */
            Section::make('Presensi Waktu Pulang')
                ->schema([
                    DatePicker::make('presenceOut.presence_date')
                        ->label('Tanggal Pulang'),

                    TimePicker::make('presenceOut.presence_time')
                        ->label('Waktu Pulang'),

                    Action::make('removePresenceOut')
                        ->label('Remove')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => filled($record?->presenceOut))
                        ->action(function ($record, $set) {
                            $record->presenceOut?->delete();
                            $set('presenceOut_id', null);

                            Notification::make()
                                ->success()
                                ->title('Presensi Pulang dihapus')
                                ->send();
                        }),
                ]),
        ]);
    }
}
