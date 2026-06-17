<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 💡 SMART SYSTEM: Mengeksekusi query khusus ke database mysql_master
        if (Schema::connection('mysql_master')->hasTable('tbl_employee')) {
            Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
                if (!Schema::connection('mysql_master')->hasColumn('tbl_employee', 'company')) {
                    $table->string('company')->nullable()->after('role');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::connection('mysql_master')->hasTable('tbl_employee')) {
            Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
                if (Schema::connection('mysql_master')->hasColumn('tbl_employee', 'company')) {
                    $table->dropColumn('company');
                }
            });
        }
    }
};
