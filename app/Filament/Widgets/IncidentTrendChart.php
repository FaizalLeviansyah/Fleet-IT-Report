<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class IncidentTrendChart extends ChartWidget
{
    // 👇 TAMBAHKAN BARIS INI AGAR GRAFIK MENGAMBIL LEBAR PENUH (FIT LAYAR) 👇
    protected int | string | array $columnSpan = '1';
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
