<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetLog extends Model
{
    protected $fillable = ['asset_id', 'user_id', 'action', 'changes'];
    protected $casts = ['changes' => 'array'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
