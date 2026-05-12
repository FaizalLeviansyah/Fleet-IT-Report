<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Format: TCK-20260511-0001
            $table->string('title');
            $table->text('description');

            // Status Pipeline
            $table->enum('status', ['New', 'Processing', 'Solved', 'Withdrawn'])->default('New');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->string('category'); // Misal: Operation > Vessel, Hardware, Network

            // Aktor yang terlibat
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            // Relasi ke Aset (Sangat Penting untuk Penghitung Downtime!)
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null');

            // Timestamp Otomatis
            $table->timestamp('resolved_at')->nullable(); // Waktu pasti tiket selesai (Argo Stop)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_tickets');
    }
};
