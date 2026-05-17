<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanKapal extends Model
{
    // Arahkan ke jembatan CCTV
    protected $connection = 'mysql_cctv';
    protected $table = 'catatan_kapal';

    // Izinkan semua kolom diisi
    protected $guarded = [];
}
