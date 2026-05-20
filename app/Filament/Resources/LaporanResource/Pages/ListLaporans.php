<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

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
