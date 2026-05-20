<?php

namespace App\Filament\Resources\IncidentReportResource\Pages;

use App\Filament\Resources\IncidentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidentReports extends ListRecords
{
    protected static string $resource = IncidentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 👇 TAMBAHKAN SLIDEOVER & UKURAN DI SINI 👇
            Actions\CreateAction::make()
                ->slideOver() // Modal akan meluncur elegan dari sisi kanan layar
                ->modalWidth('4xl'), // Memperlebar modal agar form Anda muat
        ];
    }
}
