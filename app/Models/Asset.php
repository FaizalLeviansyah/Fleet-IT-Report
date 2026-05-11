<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    // Daftarkan semua kolom termasuk kolom GLPI v2 yang baru
    protected $fillable = [
        'vessel_id', 'category_id', 'location_id', 'asset_type', 'asset_name',
        'manufacturer', 'model', 'ip_address', 'mac_address', 'serial_number',
        'status', 'hardware_uuid', 'os_version', 'cpu_model', 'total_ram',
        'disk_space', 'current_user', 'contact_person', 'group_name',
        'last_boot_time', 'software_list', 'last_seen'
    ];

    protected $casts = [
        'software_list' => 'array',
    ];

    // Relasi ke Kapal
    public function vessel() {
        return $this->belongsTo(Vessel::class);
    }

    // Relasi ke Kategori (Ini yang bikin error tadi!)
    public function category() {
        return $this->belongsTo(AssetCategory::class);
    }

    // Relasi ke Lokasi
    public function location() {
        return $this->belongsTo(AssetLocation::class);
    }
}
