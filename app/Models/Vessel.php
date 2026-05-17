<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vessel extends Model
{
    // Beri tahu Laravel bahwa tabelnya bernama 'vessels' di database utama
    protected $table = 'vessels';

    // Izinkan semua kolom diisi (mass assignment)
    protected $guarded = [];
}
