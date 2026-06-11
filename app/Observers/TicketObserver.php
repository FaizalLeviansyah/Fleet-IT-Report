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
        // Notif ke Requester (Pegawai yang lapor)
        if ($ticket->requester && $ticket->requester->phone) {
            $msgUser = "👋 Halo {$ticket->requester->full_name},\n\n";
            $msgUser .= "Tiket Anda berhasil dibuat.\n";
            $msgUser .= "🎫 *No. Tiket:* {$ticket->ticket_number}\n";
            $msgUser .= "📌 *Kendala:* {$ticket->name}\n\n";
            $msgUser .= "Tim IT akan segera menjadwalkan teknisi untuk Anda. Pantau terus di Portal ITSM!";
            
            WhatsAppService::sendMessage($ticket->requester->phone, $msgUser);
        }

        // Notif ke SPV IT (Dispatcher) agar segera nge-assign teknisi
        $msgSpv = "🚨 *TIKET IT BARU* 🚨\n\n";
        $msgSpv .= "Dari: {$ticket->requester->full_name}\n";
        $msgSpv .= "Kendala: {$ticket->name}\n";
        $msgSpv .= "Harap segera login ke Filament untuk assign teknisi.";

        // Nanti ganti dengan nomor WA Pak Hendri/Ridho
        WhatsAppService::sendMessage('081234567890', $msgSpv); 
    }

    /**
     * Dijalankan OTOMATIS saat ada PERUBAHAN pada Tiket.
     */
    public function updated(Ticket $ticket)
    {
        // Jika status berubah menjadi 5 atau 6 (Resolved/Closed)
        if ($ticket->wasChanged('status') && in_array($ticket->status, [5, 6])) {
            if ($ticket->requester && $ticket->requester->phone) {
                $msg = "✅ *TIKET DISELESAIKAN* ✅\n\n";
                $msg .= "Halo {$ticket->requester->full_name},\n";
                $msg .= "Tiket *{$ticket->ticket_number}* telah diselesaikan oleh Tim IT.\n\n";
                $msg .= "Mohon login ke Portal ITSM untuk klik APPROVE agar tiket ditutup. Terima kasih!";
                
                WhatsAppService::sendMessage($ticket->requester->phone, $msg);
            }
        }
    }
}