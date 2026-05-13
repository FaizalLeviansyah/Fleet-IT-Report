<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    // 1. Koneksi Database Mutlak
    protected $connection = 'mysql_master';
    protected $table = 'tbl_employee';
    protected $primaryKey = 'employee_id';
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 2. KUNCI UTAMA (Mencegah Hantu Session)
    // Memberitahu Laravel bahwa ID kita namanya 'employee_id', bukan 'id'
    public function getAuthIdentifierName()
    {
        return 'employee_id';
    }

    // 3. Hak Akses
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active == 1 && $this->access_app_IT_Management_System == 1;
    }

    // 4. Nama di Layar Filament
    public function getFilamentName(): string
    {
        return $this->full_name ?? 'Super Administrator';
    }

    // 5. Nama Cadangan
    public function getNameAttribute()
    {
        return $this->full_name ?? 'Super Administrator';
    }
}
