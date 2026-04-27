<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalActualTask extends Model
{
    use HasFactory;
    protected $fillable = ['personal_it_report_id', 'task_date', 'task_name', 'result', 'status', 'notes'];
}
