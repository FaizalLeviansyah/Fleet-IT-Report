<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\IncidentReport;
use App\Models\Asset;
use App\Models\Vessel;

class DashboardStatsWidget extends BaseWidget
{
    // Agar tampil di bawah Welcome Widget
    protected static ?int $sort = 1; 
    
    // Refresh otomatis setiap 10 detik tanpa perlu reload browser!
    protected static ?string $pollingInterval = '10s'; 

    protected function getStats(): array
    {
        // 1. Ambil Data Real dari Database
        $totalVessels = Vessel::count();
        $totalAssets = Asset::count();
        
        // Menghitung tiket yang belum selesai (Open & In Progress)
        $activeIncidents = IncidentReport::whereIn('status', ['Open', 'In Progress'])->count();
        
        // Menghitung persentase SLA (Contoh logika: Jika tiket aktif > 10, itu bahaya)
        $incidentColor = $activeIncidents > 5 ? 'danger' : 'success';
        $incidentIcon = $activeIncidents > 5 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        return [
            Stat::make('Tiket Insiden Aktif', $activeIncidents)
                ->description($activeIncidents > 0 ? 'Perlu segera ditangani' : 'Semua sistem aman')
                ->descriptionIcon($incidentIcon)
                ->color($incidentColor)
                ->chart([7, 3, 4, 5, 6, 3, $activeIncidents]), // Grafik mini

            Stat::make('Total Aset IT Terlacak', $totalAssets)
                ->description('Database inventaris ITAM')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('info')
                ->chart([10, 20, 30, 40, 50, 60, $totalAssets]),

            Stat::make('Total Master Kapal', $totalVessels)
                ->description('Kapal Amarin yang terdaftar')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),
        ];
    }
}