<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Judul Problem
            $table->longText('description')->nullable(); // Penjelasan akar masalah (Root Cause)
            $table->tinyInteger('status')->default(1); // 1 = New, 2 = Processing, 3 = Solved
            $table->unsignedBigInteger('assigned_to_id')->nullable(); // Teknisi yang menangani (tanpa constraint database lintas server)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};
