<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Employee;
use App\Models\Company;
use App\Models\PresenceIn;
use App\Models\PresenceOut;

class PresencesStatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Employee', Employee::count())
                ->description('Jumlah semua karyawan')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Total Company', Company::count())
                ->description('Perusahaan terdaftar')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),

            Stat::make('Total Absen Masuk', PresenceIn::count())
                ->description('Jumlah presensi masuk')
                ->descriptionIcon('heroicon-o-arrow-down-circle')
                ->color('primary'),

            Stat::make('Total Absen Keluar', PresenceOut::count())
                ->description('Jumlah presensi keluar')
                ->descriptionIcon('heroicon-o-arrow-up-circle')
                ->color('danger'),
        ];
    }
}
