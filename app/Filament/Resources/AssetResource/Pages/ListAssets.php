<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Pop-Up akan muncul DI TENGAH (karena tidak ada slideOver)
            // '4xl' adalah ukurannya agar form 2 kolom tidak berantakan
            \Filament\Actions\CreateAction::make()
                ->modalWidth('4xl'),
        ];
    }
}
