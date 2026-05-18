<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CctvSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth'; // Ikon Gear
    protected static ?string $navigationGroup = 'CCTV Monitoring';
    protected static ?string $navigationLabel = 'Pengaturan Kamera';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.cctv-settings';

    // Fitur Keamanan: Hanya Admin yang bisa atur CCTV
    public static function canAccess(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
