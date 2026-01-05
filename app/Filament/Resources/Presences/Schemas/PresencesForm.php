<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /* ======================
                   TOTAL TIME
                ====================== */
                TextInput::make('total_time')
                    ->label('Total Time')
                    ->formatStateUsing(fn ($state) => abs($state))
                    ->numeric(),

                /* ======================
                   EMPLOYEE
                ====================== */
                Select::make('employees_id')
                    ->relationship('employee', 'full_name')
                    ->label('Nama Karyawan')
                    ->disabled()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelUsing(fn ($record) => $record->full_name ?? '—'),

                Select::make('company_id')
                    ->relationship('company', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->label('Perusahaan')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelUsing(fn ($record) => $record->name ?? '—'),

                Select::make('position_id')
                    ->relationship('position', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->label('Posisi')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->getOptionLabelUsing(fn ($record) => $record->name ?? '—'),

                /* ======================
                   PRESENCE IN
                ====================== */
                Section::make('Presensi Waktu Masuk')
                    ->relationship('presenceIn')
                    ->schema([
                        DatePicker::make('presence_date')
                            ->label('Tanggal Masuk')
                            ->required(),
                        TimePicker::make('presence_time')
                            ->label('Waktu Masuk')
                            ->required(),
                    ]),

                /* ======================
                   PRESENCE OUT
                ====================== */
                Section::make('Presensi Waktu Pulang')
                    ->relationship('presenceOut')
                    ->schema([
                        DatePicker::make('presence_date')
                            ->label('Tanggal Pulang'),
                        TimePicker::make('presence_time')
                            ->label('Waktu Pulang'),
                    ]),
            ]);
    }
}
