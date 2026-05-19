<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification;

class CustomNotification extends DatabaseNotification
{
    // 👇 PAKSA BACA DARI DATABASE LOKAL (IT), BUKAN HRD
    protected $connection = 'mysql'; 
}