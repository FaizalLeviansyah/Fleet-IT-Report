<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalPlannedTask extends Model
{
    protected $guarded = []; // 👈 INI JUGA WAJIB
    protected $casts = ['tasks' => 'array'];
}
