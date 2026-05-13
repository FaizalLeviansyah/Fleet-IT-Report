<?php

namespace App\Filament\Resources\IncidentTicketResource\Pages;

use App\Filament\Resources\IncidentTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncidentTicket extends EditRecord
{
    protected static string $resource = IncidentTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
