<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Menyasar koneksi dan tabel yang benar
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
            if (!Schema::connection('mysql_master')->hasColumn('tbl_employee', 'role')) {
                $table->string('role')->default('employee')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
            if (Schema::connection('mysql_master')->hasColumn('tbl_employee', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
