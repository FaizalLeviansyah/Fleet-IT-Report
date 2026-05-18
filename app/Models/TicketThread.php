<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketThread extends Model
{
    protected $guarded = [];

    // Relasi balik ke Laporan Insiden
    public function incident()
    {
        return $this->belongsTo(IncidentReport::class, 'ticket_id');
    }

    // Relasi ke User (siapa yang membalas)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
