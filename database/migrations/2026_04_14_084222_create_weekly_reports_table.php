<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->cascadeOnDelete();
            $table->integer('employee_id')->nullable(); // ID dari user yang login (SSO)
            $table->date('report_date');

            // 1. Availability
            $table->string('vessel_status')->nullable();
            $table->decimal('uptime_percentage', 5, 2)->nullable();
            $table->string('sla_compliance')->nullable();

            // 2. Incident
            $table->text('incident_list')->nullable();
            $table->text('root_cause')->nullable();

            // 3. Maintenance
            $table->string('maintenance_type')->nullable();
            $table->text('preventive_maintenance')->nullable();

            // 4-9. Scope Lainnya
            $table->text('performance_trend')->nullable();
            $table->text('risk_identification')->nullable();
            $table->text('activity_log')->nullable();
            $table->text('inventory_tracking')->nullable();

            // Status Laporan (1 = Draft, 3 = Final/Completed)
            $table->integer('status')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
