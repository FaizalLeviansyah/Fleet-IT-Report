<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    // Arahkan ke koneksi database master
    protected $connection = 'mysql_master';

    // Arahkan ke tabel pegawai
    protected $table = 'tbl_employee';

    // Tentukan primary key-nya
    protected $primaryKey = 'employee_id';

    // Sembunyikan kolom sensitif saat data ditarik
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
