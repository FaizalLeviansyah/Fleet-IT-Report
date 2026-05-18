<?php

namespace App\Filament\Resources\LaporanPegawaiResource\Pages;

use App\Filament\Resources\LaporanPegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanPegawais extends ListRecords
{
    protected static string $resource = LaporanPegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
