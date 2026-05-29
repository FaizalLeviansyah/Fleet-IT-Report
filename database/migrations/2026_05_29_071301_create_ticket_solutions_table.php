<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_solutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->unsignedBigInteger('user_id'); // Teknisi yang memberikan solusi (Tanpa gembok constraint)
            $table->longText('content'); // Penjelasan perbaikan
            $table->tinyInteger('status')->default(1); // 1 = Waiting Approval, 2 = Approved (Closed), 3 = Refused (Buka lagi)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_solutions');
    }
};
