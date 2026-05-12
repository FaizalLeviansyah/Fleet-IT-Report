<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketThread extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'type', 'content'];

    public function ticket() { return $this->belongsTo(IncidentTicket::class); }
    public function user() { return $this->belongsTo(User::class); }
}
