<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vessel;
use App\Models\Laporan;
use Carbon\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    // Mengatur urutan agar Widget ini muncul di posisi paling atas halaman Dashboard
    protected static ?int $sort = 1;

    // Opsional: Membuat widget membentang rapi
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            // 1. KOTAK TOTAL KAPAL
            Stat::make('Total Master Kapal', Vessel::count())
                ->description('Jumlah kapal yang terdaftar di sistem')
                ->descriptionIcon('heroicon-m-globe-asia-australia') // Ikon bola dunia
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Animasi grafik (dummy visual)
                ->color('success'),

            // 2. KOTAK TOTAL LAPORAN CCTV
            Stat::make('Total Laporan CCTV', Laporan::count())
                ->description('Total riwayat laporan dari seluruh kapal')
                ->descriptionIcon('heroicon-m-document-duplicate') // Ikon dokumen
                ->chart([1, 4, 2, 7, 5, 10, 12]) // Animasi grafik
                ->color('primary'),

            // 3. KOTAK LAPORAN BULAN INI
            Stat::make('Laporan Bulan Ini', Laporan::whereMonth('waktu_kejadian', Carbon::now()->month)
                                                    ->whereYear('waktu_kejadian', Carbon::now()->year)
                                                    ->count())
                ->description('Laporan masuk di bulan berjalan')
                ->descriptionIcon('heroicon-m-calendar-days') // Ikon kalender
                ->chart([3, 1, 4, 2, 8, 5, 9]) // Animasi grafik
                ->color('warning'),
        ];
    }
}
