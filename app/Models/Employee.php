<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\FilamentUser; 
use Filament\Panel;

class Employee extends Authenticatable implements FilamentUser
{
    // Arahkan ke koneksi Master HRD
    protected $connection = 'mysql_master';

    // NAMA TABEL YANG BENAR:
    protected $table = 'tbl_employee';

    // Beritahu Laravel kalau ID-nya beda
    protected $primaryKey = 'employee_id';

    protected $guarded = [];

    // 👇 Tambahkan fungsi ini untuk mengunci gerbang panel
    public function canAccessPanel(Panel $panel): bool
    {
        // Hanya yang ber-role 'Admin' yang boleh melihat panel Filament
        return $this->role === 'Admin' || $this->role === 'admin';
    }
}
