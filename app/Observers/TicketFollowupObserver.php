<?php

namespace App\Observers;

use App\Models\TicketFollowup;
use App\Services\WhatsAppService;

class TicketFollowupObserver
{
    public function created(TicketFollowup $followup)
    {
        $ticket = $followup->ticket;
        $pengirim = $followup->user;

        // Jika yang membalas adalah Tim IT (Teknisi), kirim WA ke Pegawai
        if ($pengirim->is_it_team == 1 || $pengirim->role === 'Admin') {
            if ($ticket->requester && $ticket->requester->phone) {
                $msg = "💬 *UPDATE TIKET {$ticket->ticket_number}*\n\n";
                $msg .= "Tim IT membalas:\n";
                $msg .= "_\"{$followup->message}\"_\n\n";
                $msg .= "Cek dan balas melalui Portal ITSM Amarin.";

                WhatsAppService::sendMessage($ticket->requester->phone, $msg);
            }
        } 
        // Jika yang membalas adalah Pegawai (Requester), kirim WA ke Teknisi
        else {
            if ($ticket->technician && $ticket->technician->phone) {
                $msg = "💬 *REPLY DARI USER ({$ticket->ticket_number})*\n\n";
                $msg .= "{$pengirim->full_name} membalas:\n";
                $msg .= "_\"{$followup->message}\"_\n\n";
                $msg .= "Segera cek di Panel Admin Filament.";

                WhatsAppService::sendMessage($ticket->technician->phone, $msg);
            }
        }
    }
}