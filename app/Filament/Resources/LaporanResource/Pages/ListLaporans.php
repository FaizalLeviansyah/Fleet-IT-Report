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
            // Membuat aksi Create muncul sebagai Panel Samping (SPA Real-time)
            Actions\CreateAction::make()
                ->label('New Laporan')
                ->icon('heroicon-o-plus')
                ->slideOver()
                ->modalWidth('2xl')
                ->successNotificationTitle('Laporan Baru Berhasil Disimpan!'),
        ];
    }
}
