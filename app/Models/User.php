<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $connection = 'mysql_master'; 
    protected $table = 'tbl_employee';
    protected $primaryKey = 'employee_id';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];

    // 🚨 INI YANG DIMODIFIKASI: Kunci Mutlak Gerbang Panel Filament 🚨
    public function canAccessPanel(Panel $panel): bool
    {
        // Syarat mutlak masuk Dasbor Admin Filament:
        // 1. Akun berstatus Aktif
        // 2. Diberi akses ke aplikasi ITSM
        // 3. WAJIB memiliki role 'Admin' (User Biasa akan ditendang / 403 Forbidden)
        return $this->is_active == 1 
            && $this->access_app_IT_Management_System == 1 
            && ($this->role === 'Admin' || $this->role === 'admin');
    }

    public function getFilamentName(): string
    {
        return $this->full_name;
    }

    public function setPasswordAttribute($value)
    {
        if (filled($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // 👇 HUBUNGKAN KE MODEL NOTIFIKASI KUSTOM KITA
    public function notifications()
    {
        return $this->morphMany(\App\Models\CustomNotification::class, 'notifiable');
    }
}