<?php

namespace App\Filament\Resources\Presences\Tables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
use app\Models\Presences;
use app\Models\Employee;

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
                TextColumn::make('employee.full_name')
                    ->label('Nama Lengkap')
                    ->numeric()
                    ->toggleable()
                    ->sortable(),
               TextColumn::make('total_time')
                    ->label('Total Waktu (Menit)')
                    ->formatStateUsing(fn ($state) => abs($state))
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->label('Posisi')
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
                
            ])
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                        ->label('Dari Tanggal'),
                        DatePicker::make('created_until')
                        ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                        }),
                
                Filter::make('filters')
                    ->form([
                        TextInput::make('employee_name')
                            ->label('Nama Pekerja')
                            ->placeholder('Cari nama pekerja...'),

                        Select::make('employee_code_from')
                            ->label('ID Pekerja From')
                            ->options(
                                Employee::orderBy('employees_code')
                                    ->pluck('employees_code','employees_code')
                            )
                            ->searchable()
                            ->placeholder('Pilih ID Karyawan'),
                        
                        Select::make('employee_code_untill')
                            ->label('ID Pekerja To')
                            ->options(
                                Employee::orderBy('employees_code')
                                 ->pluck('employees_code', 'employees_code')
                            )
                            ->searchable()
                            ->placeholder('Pilih ID Karyawan'),

                        Select::make('employees_company_id')
                            ->label('Perusahaan')
                            ->options(function () {
                                return \App\Models\Company::pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->placeholder('Pilih perusahaan'),

                        Select::make('employees_position_id')
                            ->label('Posisi')
                            ->options(function () {
                                return \App\Models\Position::pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->placeholder('Pilih posisi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (
                            filled($data['employee_code_from']) &&
                            filled($data['employee_code_until']) &&
                            $data['employee_code_from'] > $data['employee_code_until']
                        ) {
                            [$data['employee_code_from'], $data['employee_code_until']] =
                                [$data['employee_code_until'], $data['employee_code_from']];
                        }
                        return $query
                            ->when($data['employee_code_from'], fn ($q, $from) =>
                                $q->where('employees_code', '>=', $from)
                            )
                            ->when($data['employee_code_untill'], fn ($q, $untill) =>
                                $q->where('employees_code', '>=', $untill)
                            )
                            ->when($data['employee_name'] ?? null, fn ($query, $name) => 
                                $query->whereHas('employee', fn ($q) => $q->where('full_name', 'like', "%{$name}%"))
                            )
                            ->when($data['employees_company_id'] ?? null, fn ($query, $companyId) => 
                                $query->where('employees_company_id', $companyId)
                            )
                            ->when($data['employees_position_id'] ?? null, fn ($query, $positionId) => 
                                $query->where('employees_position_id', $positionId)
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
