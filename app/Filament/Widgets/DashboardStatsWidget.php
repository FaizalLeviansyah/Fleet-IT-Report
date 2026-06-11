<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Asset;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    // Supaya widget ini selalu muncul paling atas
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Hitung Data Real-time
        $openTickets = Ticket::whereIn('status', [1, 2, 3])->count();
        $unassignedTickets = Ticket::where('status', 1)->whereNull('assigned_to')->count();
        $resolvedTickets = Ticket::whereIn('status', [5, 6])->count();
        $onlineAssets = Asset::where('status', 'Active')->count();

        return [
            Stat::make('Tiket Aktif (Open)', $openTickets)
                ->description('Tiket yang butuh penanganan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Efek grafik mini (sparkline)

            Stat::make('Tiket Unassigned', $unassignedTickets)
                ->description('Belum diambil teknisi IT')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('warning')
                ->chart([3, 5, 2, 8, 1, 4, 2]),

            Stat::make('Tiket Selesai (Resolved)', $resolvedTickets)
                ->description('Performa penyelesaian IT')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 4, 6, 8, 12, 15, 20]),

            Stat::make('Aset Online (Agent)', $onlineAssets)
                ->description('PC/Laptop terhubung ke server')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('info'),
        ];
    }
}