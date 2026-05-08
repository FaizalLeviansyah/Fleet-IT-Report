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
            $table->string('ticket_number')->unique(); // Misal: TKT-20260427-001
            $table->foreignId('vessel_id')->constrained('vessels')->onDelete('cascade');
            $table->foreignId('asset_id')->nullable()->constrained('assets')->onDelete('set null'); // Tiket ini masalah perangkat apa?
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // PIC yang mengerjakan

            $table->string('title');
            $table->text('description');
            $table->string('severity'); // Critical (Bikin kapal DOWN), High, Medium, Low
            $table->string('status')->default('Open'); // Open, In Progress, Resolved, Closed

            $table->dateTime('opened_at'); // Waktu insiden terjadi
            $table->dateTime('resolved_at')->nullable(); // Waktu perbaikan selesai
            $table->integer('downtime_minutes')->default(0); // Auto-calculate durasi perbaikan

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_tickets');
    }
};
