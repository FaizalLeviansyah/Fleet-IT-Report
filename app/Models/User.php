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

    // 1. ARAHKAN KE DATABASE MASTER HRD
    protected $connection = 'mysql_master';
    protected $table = 'tbl_employee';
    protected $primaryKey = 'employee_id';

    // Kolom apa saja yang boleh diisi (mass assignable)
    protected $guarded = [];

    // Sembunyikan password demi keamanan
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 2. LOGIKA SSO PINTAR: Siapa yang boleh masuk ke Dasbor IT?
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya bisa masuk jika: Pegawai Aktif (is_active = 1) DAN Punya Akses IT (access_app_IT_Management_System = 1)
        return $this->is_active == 1 && $this->access_app_IT_Management_System == 1;
    }

    // 3. Beritahu Filament nama user-nya diambil dari kolom 'full_name' (bukan 'name')
    public function getFilamentName(): string
    {
        return $this->full_name;
    }
}
