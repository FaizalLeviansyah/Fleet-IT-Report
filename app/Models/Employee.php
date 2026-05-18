<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    // Arahkan ke koneksi Master HRD
    protected $connection = 'mysql_master';

    // NAMA TABEL YANG BENAR:
    protected $table = 'tbl_employee';

    // Beritahu Laravel kalau ID-nya beda
    protected $primaryKey = 'employee_id';

    protected $guarded = [];
}
