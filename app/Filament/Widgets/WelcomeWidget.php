<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';
    protected static ?int $sort = 0; // Pastikan muncul paling atas!
    protected int | string | array $columnSpan = 'full';
}
