<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_planned_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_it_report_id')->constrained('personal_it_reports')->onDelete('cascade');
            $table->string('plan_name'); // Rencana
            $table->string('target');    // Target
            $table->string('priority');  // Tinggi / Sedang / Rendah
            $table->date('deadline')->nullable(); // Deadline
            $table->text('notes')->nullable(); // Catatan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_planned_tasks');
    }
};
