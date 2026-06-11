<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cctv_reports', function (Blueprint $table) {
            $table->id();
            $table->string('vessel_name'); // Nama Kapal
            $table->date('report_date')->nullable(); // Tanggal Laporan Mingguan
            $table->string('status')->default('Normal'); // Status Keseluruhan (Normal/Warning/Critical)
            $table->json('camera_checklist')->nullable(); // Menampung array checklist banyak kamera
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctv_reports');
    }
};