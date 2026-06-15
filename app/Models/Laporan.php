<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $connection = 'mysql_cctv';
    protected $table = 'laporan';
    protected $guarded = [];

    protected $casts = [
        'camera_checklist' => 'array',
        'waktu_kejadian' => 'datetime',
    ];

    public function gambars()
    {
        return $this->hasMany(GambarLaporan::class, 'laporan_id');
    }
}
