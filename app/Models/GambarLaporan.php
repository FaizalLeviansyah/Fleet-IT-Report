<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarLaporan extends Model
{
    protected $connection = 'mysql_cctv';
    protected $table = 'gambar_laporan';
    protected $guarded = [];

    // Relasi balik ke Laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }
}
