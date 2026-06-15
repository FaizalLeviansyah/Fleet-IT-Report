<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan koneksi mengarah ke database CCTV
        Schema::connection('mysql_cctv')->table('laporan', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['status_front1', 'status_front2', 'status_back1', 'status_back2']);

            // Tambahkan kolom standar Fleet CMDR
            $table->string('status_ajg')->default('Clear')->after('status_ccr');
            $table->string('status_brt')->default('Clear')->after('status_ajg');
            $table->string('status_ecr')->default('Clear')->after('status_brt');
            $table->string('status_wkn')->default('Clear')->after('status_ecr');
            $table->string('status_wkr')->default('Clear')->after('status_wkn');
        });
    }
};
