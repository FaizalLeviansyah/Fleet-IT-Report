<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CctvReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_name',
        'report_date',
        'status',
        'camera_checklist',
    ];

    // Wajib di-cast ke array agar fitur Repeater (Checklist) Filament berfungsi
    protected $casts = [
        'camera_checklist' => 'array',
    ];
}