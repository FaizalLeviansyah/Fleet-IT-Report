<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    use HasFactory;

    protected $guarded = []; // Mengizinkan semua kolom diisi

    // 👇 TAMBAHKAN BLOK INI
    protected $casts = [
        'cctv_names' => 'array',
    ];
}
