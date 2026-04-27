<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_actual_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_it_report_id')->constrained('personal_it_reports')->onDelete('cascade');
            $table->date('task_date');
            $table->string('task_name'); // Pekerjaan
            $table->string('result');    // Hasil Singkat
            $table->string('status');    // Selesai / Pending
            $table->text('notes')->nullable(); // Catatan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_actual_tasks');
    }
};
