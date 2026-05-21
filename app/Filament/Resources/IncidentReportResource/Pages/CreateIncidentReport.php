<?php

namespace App\Filament\Resources\IncidentReportResource\Pages;

use App\Filament\Resources\IncidentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateIncidentReport extends CreateRecord
{
    protected static string $resource = IncidentReportResource::class;

    protected function afterCreate(): void
    {
        // Menyimpan log ke Lonceng Notifikasi pengguna yang sedang login
        Notification::make()
            ->title('Tiket Baru Berhasil Dibuat!')
            ->body('Anda telah membuat laporan insiden baru di sistem ITSM.')
            ->icon('heroicon-o-document-check')
            ->iconColor('success')
            ->sendToDatabase(auth()->user()); // INI KUNCI AGAR MASUK KE LONCENG!
    }
}
