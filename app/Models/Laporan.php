<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $connection = 'mysql_cctv';
    protected $table = 'laporan';
    protected $guarded = [];

    // 👇 TAMBAHKAN INI AGAR CHECKLIST BISA DISIMPAN SEBAGAI ARRAY
    protected $casts = [
        'camera_checklist' => 'array',
    ];

    // INI WAJIB ADA AGAR FILAMENT BISA MENARIK GAMBARNYA!
    public function gambars()
    {
        return $this->hasMany(GambarLaporan::class, 'laporan_id');
    }
}