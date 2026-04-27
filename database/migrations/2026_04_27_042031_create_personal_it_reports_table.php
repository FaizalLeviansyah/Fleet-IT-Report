<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personal_it_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date'); // Tanggal mulai (Senin)
            $table->date('end_date');   // Tanggal akhir (Jumat/Minggu)
            $table->integer('status')->default(1); // 1 = Draft, 3 = Final
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_it_reports');
    }
};
