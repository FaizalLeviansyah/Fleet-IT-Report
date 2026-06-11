<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Tiket';
    protected static ?int $sort = 2;
    
    // Mengecilkan ukuran chart agar tidak memakan layar
    protected static ?array $options = [
        'maintainAspectRatio' => false,
    ];

    protected function getData(): array
    {
        $new = Ticket::where('status', 1)->count();
        $progress = Ticket::whereIn('status', [2, 3, 4])->count();
        $resolved = Ticket::whereIn('status', [5, 6])->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tiket',
                    'data' => [$new, $progress, $resolved],
                    'backgroundColor' => [
                        '#ef4444', // Merah untuk New
                        '#f59e0b', // Kuning untuk Progress
                        '#10b981', // Hijau untuk Resolved/Closed
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['New', 'In Progress', 'Resolved / Closed'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Grafik Donat
    }
}