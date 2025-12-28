<?php

namespace App\Filament\Resources\Presences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

use app\Models\Presences;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use pxlrbt\FilamentExcel\Actions\Tables\ExportAction; 
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class PresencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
           ->heading('Daftar Absensi ')
        
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
                            ->label('Nama Karyawan')
                            ->placeholder('Cari nama karyawan...'),

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
                        return $query
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
                $records->each(fn ($record) => $record->delete());
            }),
    ]),
])

            ->headerActions([
                 
                ExportAction::make()->exports([
                    ExcelExport::make('table')->fromTable(),
                   
                ]),
                DeleteBulkAction::make(),
            ])
            ->bulkActions([
                ExportBulkAction::make()
            ]);
            
    }
}
