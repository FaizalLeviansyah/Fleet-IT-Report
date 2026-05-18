<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\IncidentReport;

class IncidentChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Statistik Status Insiden (ITSM)';
    protected static ?int $sort = 2; // Tampil di bawah kotak angka

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Total Insiden',
                    'data' => [
                        IncidentReport::where('status', 'Open')->count(),
                        IncidentReport::where('status', 'In Progress')->count(),
                        IncidentReport::where('status', 'Resolved')->count(),
                    ],
                    'backgroundColor' => ['#ef4444', '#f59e0b', '#10b981'], // Merah, Kuning, Hijau
                ],
            ],
            'labels' => ['Open', 'In Progress', 'Resolved'],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Bisa diganti 'bar' atau 'line'
    }
}
