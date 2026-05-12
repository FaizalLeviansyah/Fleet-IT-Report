<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('incident_tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siapa yang nulis

            // Tipe Answer ala GLPI
            $table->enum('type', ['Reply', 'Task', 'Document', 'Solution']);

            $table->text('content'); // Isi dari task/solusinya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_threads');
    }
};
