<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HelpSop extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.pages.help-sop';
    protected static ?string $navigationLabel = 'Help / SOP';
    protected static ?string $title = 'Help / SOP';
    protected static ?string $navigationGroup = 'My Profile & Support';
    protected static ?int $sort = 100;
}
