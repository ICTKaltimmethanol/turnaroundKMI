<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;

use App\Models\Employee;
use App\Models\QrCode;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action; 


class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('qrCode.img_path')
                    ->label('Barcode')
                    ->disk('public') 
                    ->height(40)
                    ->toggleable()
                    ->circular(false)
                    ->toggleable(),
                TextColumn::make('employees_code')
                    ->label('Employee Code')
                    ->searchable()                    
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('position.name')
                    ->label('Position')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inactive' => 'warning',
                        'active' => 'success',
                        'suspended' => 'danger',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
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
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
