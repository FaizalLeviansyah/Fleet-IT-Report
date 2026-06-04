<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketsByStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Tiket';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tiket',
                    'data' => [
                        Ticket::where('status', 1)->count(),
                        Ticket::where('status', 2)->count(),
                        Ticket::where('status', 4)->count(),
                        Ticket::where('status', 5)->count(),
                    ],
                    'backgroundColor' => ['#ef4444', '#f59e0b', '#6b7280', '#10b981'],
                ],
            ],
            'labels' => ['New', 'Assigned', 'Pending', 'Solved'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
