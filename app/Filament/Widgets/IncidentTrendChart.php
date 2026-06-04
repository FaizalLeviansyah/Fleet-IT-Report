<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class IncidentTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Insiden (7 Hari Terakhir)';

    // 👇 KUNCI 1: Samakan sort dengan Grafik Donat agar bersebelahan
    protected static ?int $sort = 2;

    // 👇 KUNCI 2: Paksa hanya mengambil 1 kolom (setengah layar)
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Looping mundur dari 6 hari yang lalu sampai hari ini
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');

            // Hitung tiket masuk per harinya
            $data[] = Ticket::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tiket Masuk',
                    'data' => $data,
                    'borderColor' => '#3b82f6', // Biru
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // Biru transparan
                    'fill' => true,
                    'tension' => 0.4, // Membuat garis grafiknya melengkung (smooth)
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
