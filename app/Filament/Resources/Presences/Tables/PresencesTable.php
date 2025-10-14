<?php

namespace App\Filament\Resources\Presences\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

use Illuminate\Database\Eloquent\Builder;

class PresencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
        
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Nama Lengkap')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_time')
                    ->numeric()
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
