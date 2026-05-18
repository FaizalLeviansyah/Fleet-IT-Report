<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalPlannedTask extends Model
{
    protected $guarded = [];

    // Relasi balik ke Report Utama
    public function report()
    {
        return $this->belongsTo(PersonalItReport::class, 'personal_it_report_id');
    }
}
