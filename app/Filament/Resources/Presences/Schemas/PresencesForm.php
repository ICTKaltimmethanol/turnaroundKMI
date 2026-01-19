<?php

namespace App\Filament\Resources\Presences\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
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
                ->disabled()
                ->dehydrated(),

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
                            ->action(function ($record, Set $set) {
                                
                                $set('presence_date', null);
                                $set('presence_time', null);

                                $set('../../total_time', null);
                                $set('../../presenceOut_id', null);

                                $record->update([   
                                    'total_time' => null,
                                    'presenceOut_id' => null,
                                ]);
                    
                                $record->presenceOut()?->delete();

                                Notification::make()
                                    ->title('Berhasil')
                                    ->body('Waktu pulang berhasil dihapus.')
                                    ->success()
                                    ->send();
                            }),
                ]),
        ]);
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
