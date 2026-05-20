<?php

namespace App\Filament\Widgets;

use App\Models\IncidentReport;
use App\Models\Vessel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    // 👇 SAKLAR AUTO-REFRESH SETIAP 10 DETIK 👇
    protected static ?string $pollingInterval = '10s';
    protected static ?int $sort = 1; // Urutan paling atas

    protected function getStats(): array
    {
        // Menghitung data langsung dari Database
        $openTickets = IncidentReport::whereIn('status', ['Open', 'In Progress'])->count();
        $resolvedTickets = IncidentReport::whereIn('status', ['Resolved', 'Closed'])->count();
        $totalVessels = Vessel::count();

        return [
            Stat::make('Tiket Aktif (Belum Selesai)', $openTickets)
                ->description('Menunggu penanganan Tim IT')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]), // Garis grafik mini (sparkline)

            Stat::make('Tiket Terselesaikan', $resolvedTickets)
                ->description('Insiden berhasil ditangani')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 3, 4, 7, 8, 9, 10, 12]),

            Stat::make('Total Armada Kapal', $totalVessels)
                ->description('Kapal terdaftar di sistem')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info')
                ->chart([2, 2, 2, 2, 2, 2, 2, 2]),
        ];
    }
}
