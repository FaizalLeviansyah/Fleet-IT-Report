<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 💡 SMART BYPASS: Cek dulu apakah tabelnya ada sebelum dimodifikasi
        if (Schema::hasTable('laporans')) {
            Schema::table('laporans', function (Blueprint $table) {
                if (!Schema::hasColumn('laporans', 'camera_checklist')) {
                    $table->json('camera_checklist')->nullable();
                }
            });
        } elseif (Schema::hasTable('laporan')) {
            Schema::table('laporan', function (Blueprint $table) {
                if (!Schema::hasColumn('laporan', 'camera_checklist')) {
                    $table->json('camera_checklist')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('laporans')) {
            Schema::table('laporans', function (Blueprint $table) {
                $table->dropColumn('camera_checklist');
            });
        } elseif (Schema::hasTable('laporan')) {
            Schema::table('laporan', function (Blueprint $table) {
                $table->dropColumn('camera_checklist');
            });
        }
    }
};
