<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalActualTask extends Model
{
    protected $guarded = []; // 👈 INI YANG MEMBUAT DATA BISA MASUK
    protected $casts = ['tasks' => 'array']; // 👈 INI AGAR JSON TERBACA
}
