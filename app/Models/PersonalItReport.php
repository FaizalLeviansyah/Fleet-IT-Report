<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalItReport extends Model
{
    protected $guarded = [];
    // Ubah group-nya menjadi:
    protected static ?string $navigationGroup = 'Laporan Kinerja IT';
    protected static ?string $navigationLabel = 'Laporan Mingguan';

    public function actualTasks()
    {
        return $this->hasMany(PersonalActualTask::class, 'personal_it_report_id');
    }

    public function plannedTasks()
    {
        return $this->hasMany(PersonalPlannedTask::class, 'personal_it_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
