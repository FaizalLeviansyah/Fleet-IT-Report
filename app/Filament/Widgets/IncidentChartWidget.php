<?php

namespace App\Filament\Widgets;

use App\Models\IncidentReport;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class IncidentTrendChart extends ChartWidget
{
    protected static ?string $heading = '📊 Tren Insiden (7 Hari Terakhir)';
    protected static ?int $sort = 2; // Urutan kedua
    protected static ?string $pollingInterval = '15s'; // Auto-refresh 15 detik

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Looping mundur 7 hari ke belakang
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M'); // Format: 15 Mei, 16 Mei

            // Hitung tiket per hari tersebut
            $data[] = IncidentReport::whereDate('created_at', $date)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Insiden Dilaporkan',
                    'data' => $data,
                    'backgroundColor' => '#3B82F6', // Biru
                    'borderColor' => '#2563EB',
                    'fill' => 'start',
                    'tension' => 0.4, // Membuat garisnya melengkung halus (smooth curve)
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Jenis grafik garis
    }
}
