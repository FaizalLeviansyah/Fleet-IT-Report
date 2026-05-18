<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Arahkan ke koneksi Master HRD (sesuai .env Anda)
    protected $connection = 'mysql_master';
    protected $table = 'employees';

    // Beritahu Laravel kalau ID-nya beda
    protected $primaryKey = 'employee_id';

    protected $guarded = [];
}
