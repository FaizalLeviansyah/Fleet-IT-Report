<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\IncidentReport;
use App\Models\Asset;
use App\Models\Vessel;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1; 
    protected static ?string $pollingInterval = '15s'; 

    // 👇 INI KUNCINYA: Memaksa Card Berjejer 4 ke samping (Desktop)
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        $totalVessels = Vessel::count();
        $totalAssets = Asset::count();
        $activeIncidents = IncidentReport::whereIn('status', ['Open', 'In Progress'])->count();
        $resolvedIncidents = IncidentReport::where('status', 'Resolved')->count(); // Tambahan data baru

        return [
            Stat::make('Tiket Aktif', $activeIncidents)
                ->description('Menunggu penanganan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($activeIncidents > 0 ? 'danger' : 'success'),

            Stat::make('Tiket Selesai', $resolvedIncidents)
                ->description('Telah diselesaikan')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Total Aset IT', $totalAssets)
                ->description('Terdata di ITAM')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('info'),

            Stat::make('Master Kapal', $totalVessels)
                ->description('Armada aktif')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('primary'),
        ];
    }
}