<?php

namespace App\Filament\Widgets;


use Filament\Widgets\ChartWidget;
use App\Models\PresenceIn;
use App\Models\PresenceOut;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PresencesChart extends ChartWidget
{
    protected ?string $heading = 'Presences Chart';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    protected ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;

    protected function getData(): array
    {   
         // Ambil 7 hari terakhir
        $dates = collect();
        $dataMasuk = collect();
        $dataKeluar = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->toDateString());

            $dataMasuk->push(PresenceIn::whereDate('presence_date', $date)->count());
            $dataKeluar->push(PresenceOut::whereDate('presence_date', $date)->count());
        }
        return [
           'datasets' => [
                [
                    'label' => 'Presensi Masuk',
                    'data' => $dataMasuk,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Presensi Keluar',
                    'data' => $dataKeluar,
                    'borderColor' => '#bd8f41ff',
                    'backgroundColor' => 'rgba(245,158,11,0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
