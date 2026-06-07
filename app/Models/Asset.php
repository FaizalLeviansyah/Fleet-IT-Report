<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    // Asumsi tabel berada di database default (bukan cctv)
    protected $table = 'assets';
    // Buka gembok agar API bisa menyimpan semua kolom dari Script PowerShell
    protected $guarded = [];

    // KUNCI SIHIR: Mengubah data text menjadi Array otomatis
    protected $casts = [
        'software_list' => 'array',
    ];

    // Relasi ke Master Kapal (jika ada)
    public function vessel()
    {
        return $this->belongsTo(Vessel::class, 'vessel_id');
    }
}
