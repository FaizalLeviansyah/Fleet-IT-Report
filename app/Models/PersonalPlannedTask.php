<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalPlannedTask extends Model
{
    use HasFactory;
    protected $fillable = ['personal_it_report_id', 'plan_name', 'target', 'priority', 'deadline', 'notes'];
}
