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
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
 

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('total_time')
                    ->label('Total Waktu (Menit)')
                    ->numeric()
                    ->disabled()   
                    ->afterStateUpdated(fn ($state, $set) =>
                        $set('total_time', abs((int) $state ))
                    )
                    ->dehydrated(),  

                /* TextInput::make('total_time')
                    ->label('Total Waktu (Menit)')
                    ->numeric()
                    ->minValue(0)
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) =>
                        $set('total_time', abs((int) $state))
                    )
                    ->dehydrated(), */

                Select::make('employees_id')
                    ->relationship('employee', 'full_name')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => $record->full_name ?? '-'
                    )
                    ->label('Nama Pekerja')
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
                        ->live()
                        ->afterStateUpdated(
                            fn( Get $get, Set $set)=>
                            self::generateTotalMinute($get,$set)
                        )
                        ->required(),
                ]),

            Section::make('Presensi Waktu Pulang')
                ->relationship('presenceOut')
                ->schema([
                    DatePicker::make('presence_date')
                        ->label('Tanggal Pulang')
                        ->required()
                        ->live()
                        ->afterStateUpdated(
                            fn( Get $get, Set $set) =>
                            self::generateTotalMinute($get,$set)
                        )
                        //->reactive()
                        ,

                    TimePicker::make('presence_time')
                        ->label('Waktu Pulang')
                        
                        ->afterStateUpdated(
                            fn( Get $get, Set $swet) =>
                            self::generateTotalMinute($get,$set)
                        )
                        ->afterStateHydrated(
                            fn(Get $get, Set $set) =>
                            self::generateTotalMinute($get,$set)
                        ),//->reactive()
                ]),
            ]);
    }
    
    
 protected static function generateTotalMinute(Get $get, Set $set): void
{
    $inDate  = $get('../../presenceIn.presence_date');
    $inTime  = $get('../../presenceIn.presence_time');
    $outDate = $get('../../presenceOut.presence_date');
    $outTime = $get('../../presenceOut.presence_time');

    if (! $inDate || ! $inTime || ! $outDate || ! $outTime) {
        return;
    }

    $start = Carbon::parse("$inDate $inTime");
    $end   = Carbon::parse("$outDate $outTime");

    // SHIFT MALAM (pulang lebih kecil dari masuk)
    if ($end->lessThan($start)) {
        $end->addDay();
    }

    $minutes = $start->diffInMinutes($end);

    // pastikan tidak minus
    $set('../../total_time', abs($minutes));
}


    
}

