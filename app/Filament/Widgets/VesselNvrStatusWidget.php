<?php

namespace App\Filament\Widgets;

use App\Models\Vessel;
use App\Models\IncidentReport;
use Filament\Widgets\Widget;

class VesselNvrStatusWidget extends Widget
{
    protected static string $view = 'filament.widgets.vessel-nvr-status-widget';
    protected static ?int $sort = 3; // Tampil di paling bawah
    // Memaksa widget hanya mengambil setengah layar di sebelah tabel
    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    // Auto-refresh setiap 15 detik tanpa reload halaman!
    protected static ?string $pollingInterval = '15s';

    protected function getViewData(): array
    {
        // Tarik data kapal dan gabungkan dengan status tiket
        $vessels = Vessel::all()->map(function ($vessel) {
            // Cek apakah kapal ini sedang punya tiket rusak
            $hasIssue = IncidentReport::where('vessel_name', $vessel->vessel_name)
                ->whereIn('status', ['Open', 'In Progress'])
                ->exists();

            return [
                'name' => $vessel->vessel_name,
                'status' => $hasIssue ? 'OFFLINE' : 'ONLINE',
                'color' => $hasIssue ? 'bg-red-500' : 'bg-emerald-500',
                'border' => $hasIssue ? 'border-red-500/50' : 'border-emerald-500/30',
                'text' => $hasIssue ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400',
                'pulse' => $hasIssue // Aktifkan animasi berkedip jika offline
            ];
        });

        return [
            'vessels' => $vessels
        ];
    }
}
