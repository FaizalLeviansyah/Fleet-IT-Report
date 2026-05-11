<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetSoftware extends Model
{
    use HasFactory;

    // Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'asset_id', 'software_name', 'version', 'publisher'
    ];

    // Relasi balik ke Asset
    public function asset() {
        return $this->belongsTo(Asset::class);
    }
}
