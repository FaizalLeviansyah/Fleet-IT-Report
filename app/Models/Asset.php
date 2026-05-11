<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id', 'asset_type', 'asset_name', 'ip_address', 'serial_number',
        'status', 'hardware_uuid', 'os_version', 'cpu_model', 'total_ram',
        'disk_space', 'software_list', 'last_seen'
    ];

    // INI PENTING: Memberitahu Laravel bahwa kolom ini berisi JSON
    protected $casts = [
        'software_list' => 'array',
    ];

    public function vessel() {
        return $this->belongsTo(Vessel::class);
    }

    // (HAPUS relasi fungsi software() yang lama, karena tabelnya sudah kita musnahkan)
}
