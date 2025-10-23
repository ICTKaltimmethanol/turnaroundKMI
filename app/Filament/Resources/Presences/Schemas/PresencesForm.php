<?php
namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use App\Models\Company;
use Filament\Schemas\Schema;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_time')
                    ->label('Total Time')
           
                    ->numeric(),
                
                Select::make('employees_id')
                    ->relationship('employee', 'full_name')
                    ->label('Nama Karyawan')
                    ->disabled()
                    ->searchable()
                    ->preload(),

                Select::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
               
                    ->required(),
                
                Select::make('position_id')
                    ->label('Posisi')
                    ->relationship('position', 'name', modifyQueryUsing: fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->required(),

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
