<?php
namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Carbon\Carbon;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('total_time')
                ->label('Total Waktu (Menit)')
                ->numeric()
                ->disabled()
                ->dehydrated(),

            Section::make('Presensi Waktu Masuk')
                ->relationship('presenceIn')
                ->schema([
                    DatePicker::make('presence_date')
                        ->label('Tanggal Masuk')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),

                    TimePicker::make('presence_time')
                        ->label('Waktu Masuk')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                ]),

            Section::make('Presensi Waktu Pulang')
                ->relationship('presenceOut')
                ->schema([
                    DatePicker::make('presence_date')
                        ->label('Tanggal Pulang')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),

                    TimePicker::make('presence_time')
                        ->label('Waktu Pulang')
                        ->required()
                        ->live()
                        ->afterStateUpdated(fn (Get $get, Set $set) =>
                            self::generateTotalMinute($get, $set)
                        ),
                ]),
        ]);
    }

    protected static function generateTotalMinute(Get $get, Set $set): void
    {
        $inDate  = $get('presenceIn.presence_date');
        $inTime  = $get('presenceIn.presence_time');
        $outDate = $get('presenceOut.presence_date');
        $outTime = $get('presenceOut.presence_time');

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
