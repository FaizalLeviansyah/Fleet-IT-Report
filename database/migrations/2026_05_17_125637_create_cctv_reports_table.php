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
            $table->string('channel');     // CH-01, CH-02, dst
            $table->string('image_path');  // Path Gambar Snapshot
            $table->timestamp('captured_at'); // Waktu snapshot diambil
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctv_reports');
    }
};