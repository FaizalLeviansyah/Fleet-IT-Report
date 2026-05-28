<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_it_reports', function (Blueprint $table) {
            // Mengubah tipe kolom status menjadi string
            $table->string('status')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Mengembalikan ke integer jika perlu (asumsi sebelumnya integer)
        Schema::table('personal_it_reports', function (Blueprint $table) {
            $table->integer('status')->change();
        });
    }
};
