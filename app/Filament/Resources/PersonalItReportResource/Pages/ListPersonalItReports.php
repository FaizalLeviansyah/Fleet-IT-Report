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
            Actions\CreateAction::make()
                ->modalWidth('7xl')
                ->label('Buat Laporan Baru')
                ->icon('heroicon-o-document-plus')
                ->successNotificationTitle('Laporan Berhasil Disimpan! 🟢'),
        ];
    }
}
