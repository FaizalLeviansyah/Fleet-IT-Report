<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Field ini digunakan untuk menautkan tiket ke sebuah Problem
            $table->foreignId('problem_id')->nullable()->constrained('problems')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['problem_id']);
            $table->dropColumn('problem_id');
        });
    }
};
