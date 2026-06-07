<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Cek dan tambah laci SATU PER SATU agar 100% kebal error
            if (!Schema::hasColumn('assets', 'cpu_usage')) {
                $table->decimal('cpu_usage', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('assets', 'ram_usage')) {
                $table->decimal('ram_usage', 5, 2)->nullable();
            }
            if (!Schema::hasColumn('assets', 'disk_usage')) {
                $table->decimal('disk_usage', 5, 2)->nullable();
            }

            // Kita TIDAK menambahkan 'software_list' karena ternyata sudah ada di database Anda!
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'cpu_usage')) {
                $table->dropColumn(['cpu_usage', 'ram_usage', 'disk_usage']);
            }
        });
    }
};
