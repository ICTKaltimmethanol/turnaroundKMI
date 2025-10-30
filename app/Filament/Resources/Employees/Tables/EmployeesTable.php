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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Position;
use App\Models\QrCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Filament\Actions\Action; 
use Filament\Tables\Actions\BulkAction;


class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('qrCode.img_path')
                    ->label('Qr Code')
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
                    ->form([
                        DatePicker::make('created_from')->label('Dari Tanggal'),
                        DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),

                // 🔹 Filter gabungan (nama, perusahaan, posisi)
                Filter::make('filters')
                    ->form([
                        TextInput::make('employee_name')
                            ->label('Nama Karyawan')
                            ->placeholder('Cari nama karyawan...'),

                        Select::make('company_id')
                            ->label('Perusahaan')
                            ->options(fn () => Company::pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Pilih perusahaan'),

                        Select::make('position_id')
                            ->label('Posisi')
                            ->options(fn () => Position::pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Pilih posisi'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['employee_name'], fn ($q, $name) =>
                                $q->where('full_name', 'like', "%{$name}%")
                            )
                            ->when($data['company_id'], fn ($q, $companyId) =>
                                $q->where('company_id', $companyId)
                            )
                            ->when($data['position_id'], fn ($q, $positionId) =>
                                $q->where('position_id', $positionId)
                            );
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                Action::make('downloadAllQRCodes')
                    ->label('Download Semua QR Code')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        $zip = new ZipArchive;
                        $fileName = 'qr_codes_' . now()->format('Ymd_His') . '.zip';
                        $zipPath = storage_path('app/public/' . $fileName);

                        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                            $employees = \App\Models\Employee::with('qrCode')->get();

                            foreach ($employees as $employee) {
                                if ($employee->qrCode && Storage::disk('public')->exists($employee->qrCode->img_path)) {
                                    $path = Storage::disk('public')->path($employee->qrCode->img_path);
                                    $fileNameInsideZip = $employee->employees_code . '_' . $employee->full_name . '.png';
                                    $zip->addFile($path, $fileNameInsideZip);
                                }
                            }

                            $zip->close();
                        }

                        // Return URL download file zip
                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Download Semua QR Code')
                    ->modalSubheading('File ZIP akan berisi seluruh QR Code karyawan.')
                    ->modalButton('Download Sekarang'),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
