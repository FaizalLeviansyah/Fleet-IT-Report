<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $guarded = [];

    // Relasi ke Aset IT
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
    // Relasi ke Thread/Diskusi
    public function threads()
    {
        return $this->hasMany(TicketThread::class, 'ticket_id');
    }
}
