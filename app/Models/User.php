<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    // Arahkan ke DB Master dan Tabel Employee
    protected $connection = 'mysql_master';
    protected $table = 'tbl_employee';
    protected $primaryKey = 'employee_id';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Fungsi Wajib Filament: Siapa yang boleh masuk?
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya yang statusnya Aktif DAN punya akses ke aplikasi IT Management
        return $this->is_active == 1 && $this->access_app_IT_Management_System == 1;
    }
}
