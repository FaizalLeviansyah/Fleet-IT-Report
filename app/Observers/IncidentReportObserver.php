<?php

namespace App\Observers;

use App\Models\IncidentReport;
use App\Models\User;
use Filament\Notifications\Notification;

class IncidentReportObserver
{
    /**
     * Logika ini akan TERPICU OTOMATIS setiap ada tiket baru yang disimpan ke database.
     */
    public function created(IncidentReport $incidentReport): void
    {
        // 1. Cari siapa saja Admin IT yang berhak menerima notifikasi
        // (Sesuai dengan logika hak akses di Model User Anda)
        $itAdmins = User::where('is_active', 1)
                        ->where('access_app_IT_Management_System', 1)
                        ->get();

        // 2. Kirim notifikasi sistem ke Lonceng mereka
        Notification::make()
            ->title('🚨 Insiden Baru: ' . $incidentReport->ticket_number)
            ->body('Kategori: ' . $incidentReport->category . ' | Kapal: ' . $incidentReport->vessel_name)
            ->warning() // Warna icon kuning/orange
            ->sendToDatabase($itAdmins);
    }

    /**
     * Logika ini TERPICU OTOMATIS jika status tiket diubah (misal: Selesai/Resolved).
     */
    public function updated(IncidentReport $incidentReport): void
    {
        // Cek apakah yang diubah adalah kolom 'status' dan statusnya menjadi 'Resolved'
        if ($incidentReport->wasChanged('status') && $incidentReport->status === 'Resolved') {
            
            $itAdmins = User::where('is_active', 1)
                            ->where('access_app_IT_Management_System', 1)
                            ->get();

            Notification::make()
                ->title('✅ Tiket Selesai: ' . $incidentReport->ticket_number)
                ->body('Insiden di ' . $incidentReport->vessel_name . ' telah berhasil diselesaikan.')
                ->success() // Warna icon hijau
                ->sendToDatabase($itAdmins);
        }
    }
}