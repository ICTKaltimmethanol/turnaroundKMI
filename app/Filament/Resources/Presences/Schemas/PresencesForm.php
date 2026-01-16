<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Carbon\Carbon;

class PresencesForm
{
    public static function form(Form $form): Form
    {
        return $form->schema([

            TextInput::make('total_time')
                ->label('Total Waktu (Menit)')
                ->numeric()
                ->disabled()
                ->dehydrated(),

            Section::make('Presensi Waktu Masuk')
                ->relationship('presenceIn')
                ->schema([
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

        // Shift malam
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $set('total_time', $start->diffInMinutes($end));
    }
}
