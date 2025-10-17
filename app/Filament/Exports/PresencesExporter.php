<?php

namespace App\Filament\Exports;

use App\Models\Presences;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class PresencesExporter extends Exporter
{
    protected static ?string $model = Presences::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
        
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getQuery()->with(['employee', 'presenceIn', 'presenceOut']);
    }

    public static function getCompletedNotificationBody(): ?string
{
    return 'Ekspor data presensi selesai!';
}


}
