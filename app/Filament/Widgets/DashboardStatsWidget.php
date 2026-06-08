<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\Vessel;
use App\Models\Laporan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class DashboardStatsWidget extends BaseWidget
{
    // Auto-refresh setiap 10 detik agar terpantau real-time
    protected static ?string $pollingInterval = '10s';
    protected static ?int $sort = 1;

    // 👇 INI KUNCI AGAR 4 CARD SEJAJAR DALAM 1 BARIS 👇
    protected function getColumns(): int
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Tiket Aktif', Ticket::whereIn('status', [1, 2, 3, 4])->count())
                ->description('Menunggu penanganan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Tiket Selesai', Ticket::whereIn('status', [5, 6])->count())
                ->description('Performa penyelesaian')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->chart([1, 3, 4, 7, 8, 9, 12, 15]),

            Stat::make('Master Kapal', Vessel::count())
                ->description('Armada di sistem')
                ->descriptionIcon('heroicon-m-globe-asia-australia')
                ->color('info'),

            Stat::make('Laporan CCTV Bulan Ini', Laporan::whereMonth('waktu_kejadian', Carbon::now()->month)->whereYear('waktu_kejadian', Carbon::now()->year)->count())
                ->description('Masuk di bulan berjalan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),
            \Filament\Widgets\StatsOverviewWidget\Stat::make('Total Laporan CCTV (All Time)', \DB::connection('mysql_cctv')->table('laporan')->count())
        ];
    }
}
