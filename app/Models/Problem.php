<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke Teknisi yang menangani
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id', 'employee_id');
    }

    // Relasi ke banyak Tiket
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'problem_id');
    }

    // LOGIC MAGIS: Auto-Cascade Resolve
    protected static function booted()
    {
        static::updated(function ($problem) {
            // Jika status Problem diubah menjadi 3 (Solved)
            if ($problem->status == 3 && $problem->isDirty('status')) {
                // Selesaikan semua tiket yang terhubung secara otomatis!
                $problem->tickets()->update(['status' => Ticket::STATUS_SOLVED]);
            }
        });
    }
}
