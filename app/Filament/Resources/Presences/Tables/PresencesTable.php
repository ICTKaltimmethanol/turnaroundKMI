<?php

namespace App\Filament\Resources\Presences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction; // untuk export biasa (header action)


class PresencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
           ->heading('Daftar Karyawan')
        
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Nama Lengkap')
                    ->numeric()
                    ->sortable(),
               TextColumn::make('total_time')
                    ->label('Total Waktu')
                    ->formatStateUsing(fn ($state) => abs($state) . ' menit')
                    ->sortable(),


                TextColumn::make('presenceIn.presence_time')
                    ->label('Waktu Masuk')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('presenceOut.presence_time')
                    ->label('Waktu Pulang')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('presenceIn.presence_date')
                    ->label('Tanggal Masuk')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('presenceOut.presence_date')
                    ->label('Tanggal Pulang')
                    ->sortable()
                    ->toggleable(),
              
            ])
            ->filters([
                
                Filter::make('created_at')
    ->schema([
        DatePicker::make('created_from'),
        DatePicker::make('created_until'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when($data['created_from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($data['created_until'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
    })
            ])
            ->recordActions([
            
                  ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
