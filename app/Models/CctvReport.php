<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CctvReport extends Model
{
    // Gunakan koneksi yang sudah Anda buat di .env
    protected $connection = 'mysql_cctv';

    // 🚨 KOREKSI: Arahkan ke tabel yang benar yaitu 'laporan'
    protected $table = 'laporan'; 
    
    protected $guarded = [];
}