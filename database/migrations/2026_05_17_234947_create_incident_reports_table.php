<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Nomor Tiket (Misal: INC-001)
            $table->string('title'); // Judul Insiden
            $table->string('vessel_name')->nullable(); // Terkait kapal apa?
            $table->string('category'); // Kategori: Network, Hardware, Software, dll
            $table->string('priority'); // Low, Medium, High, Critical
            $table->string('status')->default('Open'); // Open, In Progress, Resolved, Closed
            $table->text('description'); // Deskripsi Masalah
            $table->string('reported_by')->nullable(); // Pelapor
            $table->text('resolution_note')->nullable(); // Catatan penyelesaian
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_reports');
    }
};
