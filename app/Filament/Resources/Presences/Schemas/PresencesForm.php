<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_time')
                    ->label('Total Time')
                    ->numeric()
                    ->disabled(),

                Select::make('employees_id')
                    ->relationship('employee', 'full_name')
                    ->label('Nama Karyawan')
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

                /* =======================
                   PRESENCE IN
                ======================= */
                Section::make('Presensi Waktu Masuk')
                    ->relationship('presenceIn')
                    ->deletable() // ✅ tombol Remove
                    ->schema([
                        DatePicker::make('presence_date')
                            ->label('Tanggal Masuk')
                            ->required(),

                        TimePicker::make('presence_time')
                            ->label('Waktu Masuk')
                            ->required(),
                    ]),

                /* =======================
                   PRESENCE OUT
                ======================= */
                Section::make('Presensi Waktu Pulang')
                    ->relationship('presenceOut')
                    ->deletable() // ✅ tombol Remove
                    ->schema([
                        DatePicker::make('presence_date')
                            ->label('Tanggal Pulang'),

                        TimePicker::make('presence_time')
                            ->label('Waktu Pulang'),
                    ]),
            ]);
    }
}
