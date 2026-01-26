<?php

namespace App\Filament\Resources\Presences\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Filament – Actions
|--------------------------------------------------------------------------
*/
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

/*
|--------------------------------------------------------------------------
| Filament – Models
|--------------------------------------------------------------------------
*/
use App\Models\Presences;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| Filament – Tables
|--------------------------------------------------------------------------
*/
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

/*
|--------------------------------------------------------------------------
| Filament – Forms
|--------------------------------------------------------------------------
*/
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

/*
|--------------------------------------------------------------------------
| Filament – pxlrbt
|--------------------------------------------------------------------------
*/
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction; 
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PresencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll('10s')
            ->heading('Daftar Absensi')
            ->paginated([
                5,
                10,
                25,
                50,
                'all',
            ])
            ->columns([
                // inactive line code
                /* TextColumn::make('employee.full_name')
                    ->label('Nama Lengkap')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('employee.employees_code')
                    ->label('ID Pekerja')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->label('Posisi')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->toggleable()
                    ->sortable(), */

                TextColumn::make('employee_name')->label('Nama Lengkap'),
                TextColumn::make('employee_code')->label('ID Pekerja'),
                TextColumn::make('company_name')->label('Perusahaan'),
                TextColumn::make('position_name')->label('Posisi'),
                TextColumn::make('total_time')
                    ->label('Total Waktu (Menit)')
                    ->formatStateUsing(fn ($state) => abs($state))
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('presenceIn.presence_date')
                    ->label('Masuk')
                    ->state(fn ($record) =>
                        $record->presenceIn
                            ? $record->presenceIn->presence_date . ' ' . $record->presenceIn->presence_time
                            : '-'
                    )
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('presenceOut.presence_date')
                    ->label('Keluar')
                    ->state(fn($record) => 
                    $record->presenceOut
                        ? $record->presenceOut->presence_date . ' ' . $record->presenceOut->presence_time
                        : '-'
                    )
                    ->sortable()
                    ->toggleable(),

                /* TextColumn::make('presenceIn.presence_date')
                    ->label('Tanggal Masuk')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('presenceIn.presence_time')
                    ->label('Waktu Masuk')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('presenceOut.presence_date')
                    ->label('Tanggal Keluar')
                    ->toggleable()
                    ->sortable(),    
                TextColumn::make('presenceOut.presence_time')
                    ->label('Waktu Keluar')
                    ->toggleable()
                    ->sortable(), 
                TextColumn::make('presenceIn.created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d/m/Y H:i:s')                    
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('presenceOut.created_at')
                    ->label('Waktu Pulang')
                    ->dateTime('d/m/Y H:i:s')
                    ->toggleable()
                    ->sortable(),
                 */
            ])
            ->filters([

                    /* ================= FILTER TANGGAL ================= */
                    Filter::make('created_at')
                        ->schema([
                            DatePicker::make('created_from')
                                ->label('Dari Tanggal'),

                            DatePicker::make('created_until')
                                ->label('Sampai Tanggal'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['created_from'] ?? null,
                                    fn ($q, $date) => $q->whereDate('presenceIn.presence_date', '>=', $date)
                                )
                                ->when(
                                    $data['created_until'] ?? null,
                                    fn ($q, $date) => $q->whereDate('presenceOut.presence_date', '<=', $date)
                                );
                        }),

                /* ================= FILTER PEKERJA ================= */
                Filter::make('filters')
                    ->form([

                        /* === 1 ID PEKERJA (PRIORITAS) === */
                        Select::make('employee_code')
                            ->label('ID Pekerja')
                            ->options(
                                \App\Models\Employee::orderBy('employees_code')
                                    ->pluck('employees_code', 'employees_code')
                            )
                            ->searchable()
                            ->placeholder('Pilih ID Pekerja'),

                        /* === RANGE ID (OPSIONAL) === */
                        Select::make('employee_code_from')
                            ->label('ID Pekerja From')
                            ->options(
                                \App\Models\Employee::orderBy('employees_code')
                                    ->pluck('employees_code', 'employees_code')
                            )
                            ->searchable(),

                        Select::make('employee_code_until')
                            ->label('ID Pekerja To')
                            ->options(
                                \App\Models\Employee::orderBy('employees_code')
                                    ->pluck('employees_code', 'employees_code')
                            )
                            ->searchable(),

                        TextInput::make('employee_name')
                            ->label('Nama Pekerja')
                            ->placeholder('Cari nama pekerja...'),

                        Select::make('employees_company_id')
                            ->label('Perusahaan')
                            ->options(\App\Models\Company::pluck('name', 'id'))
                            ->searchable(),

                        Select::make('employees_position_id')
                            ->label('Posisi')
                            ->options(\App\Models\Position::pluck('name', 'id'))
                            ->searchable(),
                    ])

                    ->query(function (Builder $query, array $data): Builder {

                        /* === PRIORITAS 1 ID === */
                        if (!empty($data['employee_code'])) {
                            return $query->where('employee_code', $data['employee_code']);
                        }

                        /* === AUTO SWAP RANGE JIKA TERBALIK === */
                        if (
                            filled($data['employee_code_from'] ?? null) &&
                            filled($data['employee_code_until'] ?? null) &&
                            $data['employee_code_from'] > $data['employee_code_until']
                        ) {
                            [$data['employee_code_from'], $data['employee_code_until']] =
                                [$data['employee_code_until'], $data['employee_code_from']];
                        }

                        return $query
                            /* === RANGE ID === */
                            ->when(
                                $data['employee_code_from'] ?? null,
                                fn ($q, $from) =>
                                    $q->where('employee_code', '>=', $from)
                            )
                            ->when(
                                $data['employee_code_until'] ?? null,
                                fn ($q, $until) =>
                                    $q->where('employee_code', '<=', $until)
                            )

                            /* === NAMA === */
                            ->when(
                                $data['employee_name'] ?? null,
                                fn ($q, $name) =>
                                    $q->where('employee_name', 'like', "%{$name}%")
                            )

                            /* === PERUSAHAAN === */
                            ->when(
                                $data['employees_company_id'] ?? null,
                                fn ($q, $companyId) =>
                                    $q->where('employees_company_id', $companyId)
                            )

                            /* === POSISI === */
                            ->when(
                                $data['employees_position_id'] ?? null,
                                fn ($q, $positionId) =>
                                    $q->where('employees_position_id', $positionId)
                            );
                    }),
            ])


            ->recordActions([
                DeleteAction::make()
                    ->action(function ($record) {

                        $presenceIn  = $record->presenceIn;
                        $presenceOut = $record->presenceOut;

                        // 1️⃣ hapus child
                        $record->delete();

                        // 2️⃣ pastikan TIDAK dipakai presences lain
                        if ($presenceIn && !Presences::where('presenceIn_id', $presenceIn->id)->exists()) {
                            $presenceIn->delete();
                        }

                        if ($presenceOut && !Presences::where('presenceOut_id', $presenceOut->id)->exists()) {
                            $presenceOut->delete();
                        }
                    }),
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function (Collection $records) {

                            DB::transaction(function () use ($records) {

                                $records->each(function ($record) {

                                    $presenceIn  = $record->presenceIn;
                                    $presenceOut = $record->presenceOut;

                                    // 1️⃣ hapus presences
                                    $record->delete();

                                    // 2️⃣ hapus presence_in jika tidak dipakai lagi
                                    if ($presenceIn && !Presences::where('presenceIn_id', $presenceIn->id)->exists()) {
                                        $presenceIn->delete();
                                    }

                                    // 3️⃣ hapus presence_out jika tidak dipakai lagi
                                    if ($presenceOut && !Presences::where('presenceOut_id', $presenceOut->id)->exists()) {
                                        $presenceOut->delete();
                                    }
                                });

                            });
                        }),
                ]),
            ])

            ->headerActions([
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable(),
                   
                ]),
                DeleteBulkAction::make()
                    ->action(function (Collection $records) {

                        DB::transaction(function () use ($records) {

                            $records->each(function ($record) {

                                $presenceIn  = $record->presenceIn;
                                $presenceOut = $record->presenceOut;

                                // 1️⃣ hapus presences
                                $record->delete();

                                // 2️⃣ hapus presence_in jika tidak dipakai lagi
                                if ($presenceIn && !Presences::where('presenceIn_id', $presenceIn->id)->exists()) {
                                    $presenceIn->delete();
                                }

                                // 3️⃣ hapus presence_out jika tidak dipakai lagi
                                if ($presenceOut && !Presences::where('presenceOut_id', $presenceOut->id)->exists()) {
                                    $presenceOut->delete();
                                }
                            });

                        });
                    }),
            ])
            
            ->bulkActions([
                ExportBulkAction::make()
            ]);
            
    }
}
