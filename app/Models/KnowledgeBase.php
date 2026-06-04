<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    use HasFactory;

    protected $guarded = []; // Buka gembok keamanan

    // Relasi ke pembuat artikel
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'employee_id');
    }
}
