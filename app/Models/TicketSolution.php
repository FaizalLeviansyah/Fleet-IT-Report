<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSolution extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(Ticket::class, 'ticket_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id', 'employee_id'); }
}
