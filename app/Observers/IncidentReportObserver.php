<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Services\WhatsAppService;

class TicketObserver
{
    /**
     * Dijalankan OTOMATIS saat Tiket BARU saja dibuat.
     */
    public function created(Ticket $ticket)
    {
        // 1. Notif ke Requester (Pegawai yang lapor)
        if ($ticket->requester && $ticket->requester->phone) {
            $msgUser = "👋 Halo {$ticket->requester->full_name},\n\n";
            $msgUser .= "Tiket Anda berhasil dibuat.\n";
            $msgUser .= "🎫 *No. Tiket:* {$ticket->ticket_number}\n";
            $msgUser .= "📌 *Kendala:* {$ticket->name}\n\n";
            $msgUser .= "Tim IT (Bpk Hendri/Ridho) akan segera menjadwalkan teknisi untuk Anda. Pantau terus di Portal ITSM!";
            
            WhatsAppService::sendMessage($ticket->requester->phone, $msgUser);
        }

        // 2. Notif ke SPV IT (Dispatcher) agar segera nge-assign teknisi
        // (Bisa hardcode nomor Pak Hendri/Ridho di sini, atau ambil dari database)
        $msgSpv = "🚨 *TIKET IT BARU* 🚨\n\n";
        $msgSpv .= "Dari: {$ticket->requester->full_name}\n";
        $msgSpv .= "Kendala: {$ticket->name}\n";
        $msgSpv .= "Prioritas: " . ($ticket->priority == 3 ? '🔴 HIGH' : '🟢 Normal') . "\n\n";
        $msgSpv .= "Harap segera login ke Filament untuk assign teknisi (Farhan/Levi).";

        WhatsAppService::sendMessage('081234567890', $msgSpv); // Ganti nomor Pak Hendri
    }

    /**
     * Dijalankan OTOMATIS saat ada PERUBAHAN pada Tiket (Misal status jadi Resolved).
     */
    public function updated(Ticket $ticket)
    {
        // Jika status berubah menjadi 5 atau 6 (Resolved/Closed)
        if ($ticket->wasChanged('status') && in_array($ticket->status, [5, 6])) {
            if ($ticket->requester && $ticket->requester->phone) {
                $msg = "✅ *TIKET DISELESAIKAN* ✅\n\n";
                $msg .= "Halo {$ticket->requester->full_name},\n";
                $msg .= "Tiket *{$ticket->ticket_number}* telah diselesaikan oleh Tim IT.\n\n";
                $msg .= "Mohon login ke Portal ITSM untuk konfirmasi persetujuan (Approve) agar tiket dapat ditutup sepenuhnya. Terima kasih!";
                
                WhatsAppService::sendMessage($ticket->requester->phone, $msg);
            }
        }
    }
}