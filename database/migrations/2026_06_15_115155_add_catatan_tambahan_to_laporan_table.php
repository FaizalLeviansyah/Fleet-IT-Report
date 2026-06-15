<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan menembak ke database cctv Anda
        Schema::connection('mysql_cctv')->table('laporan', function (Blueprint $table) {
            // Menambahkan kolom catatan tambahan setelah isi laporan
            if (!Schema::connection('mysql_cctv')->hasColumn('laporan', 'catatan_tambahan')) {
                $table->text('catatan_tambahan')->nullable()->after('isi_laporan');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('mysql_cctv')->table('laporan', function (Blueprint $table) {
            $table->dropColumn('catatan_tambahan');
        });
    }
};
