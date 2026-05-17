<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarLaporan extends Model
{
    // Arahkan ke jembatan CCTV
    protected $connection = 'mysql_cctv';
    protected $table = 'gambar_laporan';

    // Izinkan semua kolom diisi
    protected $guarded = [];

    // Relasi balik ke laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
