<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LiveMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Live Monitoring';
    protected static ?string $navigationGroup = 'CCTV Monitoring';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.live-monitoring';
}
