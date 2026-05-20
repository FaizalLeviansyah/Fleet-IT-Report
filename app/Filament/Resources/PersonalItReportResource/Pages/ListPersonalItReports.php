<?php

namespace App\Filament\Resources\PersonalItReportResource\Pages;

use App\Filament\Resources\PersonalItReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPersonalItReports extends ListRecords
{
    protected static string $resource = PersonalItReportResource::class;

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
