<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalItReport extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'start_date', 'end_date', 'status'];

    // Relasi ke tabel user (Pembuat laporan)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Actual Tasks (Pekerjaan Aktual)
    public function actualTasks() {
        return $this->hasMany(PersonalActualTask::class);
    }

    // Relasi ke tabel Planned Tasks (Rencana Minggu Depan)
    public function plannedTasks() {
        return $this->hasMany(PersonalPlannedTask::class);
    }
}
