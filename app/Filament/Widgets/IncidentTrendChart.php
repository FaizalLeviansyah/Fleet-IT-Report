<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncidentTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Tiket Masuk (7 Hari Terakhir)';
    protected static ?int $sort = 3;
    
    // Bikin widget ini memanjang (lebar 2 kolom)
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Looping 7 hari ke belakang
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M'); // Format: 10 Jun
            
            // Hitung tiket yang dibuat pada tanggal tersebut
            $data[] = Ticket::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Tiket Masuk',
                    'data' => $data,
                    'fill' => 'start', // Efek warna di bawah garis
                    'borderColor' => '#3b82f6', // Biru terang
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)', // Biru transparan
                    'tension' => 0.4, // Membuat garis melengkung (smooth curve)
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Grafik Garis
    }
}