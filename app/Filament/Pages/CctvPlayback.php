<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CctvPlayback extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-backward'; // Ikon mundur
    protected static ?string $navigationGroup = 'CCTV Monitoring';
    protected static ?string $navigationLabel = 'Playback Rekaman';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.cctv-playback';
}
