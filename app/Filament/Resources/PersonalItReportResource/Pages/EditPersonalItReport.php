<?php

namespace App\Filament\Resources\PersonalItReportResource\Pages;

use App\Filament\Resources\PersonalItReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPersonalItReport extends EditRecord
{
    protected static string $resource = PersonalItReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
