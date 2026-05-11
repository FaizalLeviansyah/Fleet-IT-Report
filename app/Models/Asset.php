<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id', 'category_id', 'location_id', 'company_entity', 'asset_type', 'asset_name',
        'manufacturer', 'model', 'ip_address', 'mac_address', 'serial_number',
        'status', 'hardware_uuid', 'os_version', 'cpu_model', 'total_ram',
        'disk_space', 'current_user', 'contact_person', 'group_name',
        'last_boot_time', 'software_list', 'last_seen'
    ];

    protected $casts = [
        'software_list' => 'array',
    ];

    public function vessel() { return $this->belongsTo(Vessel::class); }
    public function category() { return $this->belongsTo(AssetCategory::class); }
    public function location() { return $this->belongsTo(AssetLocation::class); }
    public function logs() { return $this->hasMany(AssetLog::class)->orderBy('created_at', 'desc'); }

    // THE MAGIC: CCTV Database Otomatis (Model Events)
    protected static function booted()
    {
        static::created(function ($asset) {
            AssetLog::create([
                'asset_id' => $asset->id,
                'user_id' => Auth::id(),
                'action' => 'Created',
                'changes' => json_encode(['info' => 'Asset initially registered in the system'])
            ]);
        });

        static::updated(function ($asset) {
            $changes = [];
            // Deteksi kolom apa saja yang berubah
            foreach ($asset->getDirty() as $key => $newValue) {
                // Abaikan perubahan jika yang berubah hanya jam update/ping otomatis
                if (!in_array($key, ['updated_at', 'last_seen', 'last_boot_time'])) {
                    $changes[$key] = [
                        'old' => $asset->getOriginal($key),
                        'new' => $newValue
                    ];
                }
            }

            if (!empty($changes)) {
                AssetLog::create([
                    'asset_id' => $asset->id,
                    'user_id' => Auth::id(),
                    'action' => 'Updated',
                    'changes' => json_encode($changes)
                ]);
            }
        });
    }
}
