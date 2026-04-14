<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');

            // Availability
            $table->boolean('vessel_status')->default(true); // Up/Down
            $table->decimal('uptime_percentage', 5, 2);

            // Scope Status (Bisa dibikin text/enum, kita pakai text untuk simplisitas)
            $table->text('cctv_status')->nullable();
            $table->text('network_status')->nullable();
            $table->text('user_support_log')->nullable();
            $table->text('security_status')->nullable();
            $table->text('backup_system_status')->nullable();

            // Incident & Maintenance
            $table->text('incident_issues')->nullable();
            $table->text('maintenance_activities')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
