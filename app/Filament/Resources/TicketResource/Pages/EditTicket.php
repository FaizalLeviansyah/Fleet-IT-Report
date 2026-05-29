<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // 👇 INI ADALAH MAGIC TABS ALA ENTERPRISE 👇
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
