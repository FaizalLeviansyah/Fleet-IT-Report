<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentTicket extends Model
{
    protected $fillable = [
        'ticket_number', 'title', 'description', 'status', 'priority',
        'category', 'requester_id', 'assigned_to', 'asset_id', 'resolved_at'
    ];

    // Otomatis bikin nomor tiket TCK-YYYYMMDD-XXX saat data di-create
    protected static function booted()
    {
        static::creating(function ($ticket) {
            $datePrefix = now()->format('Ymd');
            $lastTicket = static::whereDate('created_at', now()->toDateString())->latest('id')->first();
            $sequence = $lastTicket ? intval(substr($lastTicket->ticket_number, -4)) + 1 : 1;
            $ticket->ticket_number = 'TCK-' . $datePrefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }

    public function requester() { return $this->belongsTo(User::class, 'requester_id'); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function asset() { return $this->belongsTo(Asset::class); }
    public function threads() { return $this->hasMany(TicketThread::class, 'ticket_id')->orderBy('created_at', 'asc'); }
}
