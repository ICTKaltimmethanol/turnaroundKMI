<?php

namespace App\Filament\Resources\Presences\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PresencesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /* ================= TOTAL TIME (ROOT) ================= */
                TextInput::make('total_time')
                    ->label('Total Waktu (Menit)')
                    ->numeric()
                    ->readOnly()     // ❗ WAJIB (bukan disabled)
                    ->dehydrated(),

                /* ================= EMPLOYEE ================= */
                Select::make('employees_id')
                    ->label('Nama Pekerja')
                    ->relationship(
                        'employee',
                        'full_name',
                        modifyQueryUsing: fn ($query) =>
                            $query->whereNotNull('full_name')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => $record->full_name ?? '-'
                    )
                    ->searchable()
                    ->preload(),

                /* ================= COMPANY ================= */
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->relationship(
                        'company',
                        'name',
                        modifyQueryUsing: fn ($query) =>
                            $query->whereNotNull('name')->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => $record->name ?? '-'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                /* ================= POSITION ================= */
                Select::make('position_id')
                    ->label('Posisi')
                    ->relationship(
                        'position',
                        'name',
                        modifyQueryUsing: fn ($query) =>
                            $query->whereNotNull('name')->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn ($record) => $record->name ?? '-'
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                /* ================= PRESENCE IN ================= */
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

                /* ================= PRESENCE OUT ================= */
                Section::make('Presensi Waktu Pulang')
                    ->relationship('presenceOut')
                    ->schema([
                        DatePicker::make('presence_date')
                            ->label('Tanggal Pulang')
                            ->reactive()
                            ->afterStateUpdated(
                                fn ($state, callable $get, callable $set) =>
                                    self::generateTotalMinute($get, $set)
                            ),

                        TimePicker::make('presence_time')
                            ->label('Waktu Pulang')
                            ->reactive()
                            ->afterStateUpdated(
                                fn ($state, callable $get, callable $set) =>
                                    self::generateTotalMinute($get, $set)
                            )
                            ->afterStateHydrated(
                                fn ($state, callable $get, callable $set) =>
                                    self::generateTotalMinute($get, $set)
                            ),
                    ]),
            ]);
    }

    /* ================= HITUNG TOTAL MENIT ================= */
    protected static function generateTotalMinute(
        callable $get,
        callable $set
    ): void {

        // ⬅⬅⬅ PERHATIKAN PATH NAIK KE ROOT
        $inDate  = $get('../../presenceIn.presence_date');
        $inTime  = $get('../../presenceIn.presence_time');
        $outDate = $get('../../presenceOut.presence_date');
        $outTime = $get('../../presenceOut.presence_time');

        if (! $inDate || ! $inTime || ! $outDate || ! $outTime) {
            $set('../../total_time', null);
            return;
        }

        $start = Carbon::parse($inDate . ' ' . $inTime);
        $end   = Carbon::parse($outDate . ' ' . $outTime);

        /**
         * SHIFT MALAM
         * Jika jam pulang < jam masuk → tambah 1 hari
         */
        if ($end->lessThan($start)) {
            $end->addDay();
        }

        $minutes = $start->diffInMinutes($end);

        // ⬅⬅⬅ SET KE ROOT STATE
        $set('../../total_time', $minutes);
    }
}
