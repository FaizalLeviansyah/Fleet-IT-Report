<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class LiveRadarWidget extends Widget
{
    // Ubah dari 'full' menjadi 1
    
    protected static string $view = 'filament.widgets.live-radar-widget';
    protected static ?int $sort = 4; // Taruh di bawah tabel
    protected int | string | array $columnSpan = '1'; // Lebar penuh
}
