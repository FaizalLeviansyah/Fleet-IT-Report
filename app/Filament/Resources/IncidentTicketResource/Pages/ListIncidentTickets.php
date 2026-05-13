<?php

namespace App\Filament\Resources\IncidentTicketResource\Pages;

use App\Filament\Resources\IncidentTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncidentTickets extends ListRecords
{
    protected static string $resource = IncidentTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
