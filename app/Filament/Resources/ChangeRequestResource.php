<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    // Buka gembok mass-assignment
    protected $guarded = [];

    // Cast tanggal agar otomatis menjadi format Carbon datetime
    protected $casts = [
        'planned_start_date' => 'datetime',
        'planned_end_date' => 'datetime',
    ];

    // Relasi ke Teknisi yang Mengajukan
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'employee_id');
    }

    // Relasi ke IT Manager yang Menyetujui
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id', 'employee_id');
    }
}
