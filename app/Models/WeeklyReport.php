<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'vessel_id', 'employee_id', 'report_date', 'vessel_status',
        'uptime_percentage', 'sla_compliance', 'incident_list', 'root_cause',
        'maintenance_type', 'preventive_maintenance', 'performance_trend',
        'risk_identification', 'activity_log', 'inventory_tracking', 'status'
    ];

    // Relasi balik ke Kapal
    public function vessel()
    {
        return $this->belongsTo(Vessel::class);
    }
}
