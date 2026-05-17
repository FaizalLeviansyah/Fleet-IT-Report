<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    // Arahkan ke jembatan CCTV
    protected $connection = 'mysql_cctv';
    protected $table = 'laporan';

    // Izinkan semua kolom diisi
    protected $guarded = [];

    // Relasi ke gambar (1 laporan punya banyak gambar)
    public function gambar()
    {
        return $this->hasMany(GambarLaporan::class, 'laporan_id');
    }
}
