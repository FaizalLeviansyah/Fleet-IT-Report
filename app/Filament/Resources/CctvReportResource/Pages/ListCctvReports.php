<?php

namespace App\Filament\Resources\CctvReportResource\Pages;

use App\Filament\Resources\CctvReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCctvReports extends ListRecords
{
    protected static string $resource = CctvReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
