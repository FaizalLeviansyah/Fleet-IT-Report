<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            // 👇 PERBAIKAN: Gunakan unsignedBigInteger biasa tanpa constrained() 👇
            $table->unsignedBigInteger('user_id');

            $table->longText('content');
            $table->integer('actiontime')->default(0);
            $table->enum('state', ['1', '2', '3'])->default('1');
            $table->boolean('is_private')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_tasks');
    }
};
