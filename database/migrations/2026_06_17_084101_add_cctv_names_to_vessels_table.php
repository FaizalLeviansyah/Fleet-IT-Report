<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            // Menambahkan kolom JSON untuk menyimpan nama-nama kamera secara dinamis
            if (!Schema::hasColumn('vessels', 'cctv_names')) {
                $table->json('cctv_names')->nullable()->after('vessel_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropColumn('cctv_names');
        });
    }
};
