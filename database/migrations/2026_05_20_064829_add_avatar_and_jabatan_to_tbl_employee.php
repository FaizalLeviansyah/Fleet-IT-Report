<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 👇 PERHATIKAN: Kita tambahkan connection('mysql_master') di sini 👇
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {

            if (!Schema::connection('mysql_master')->hasColumn('tbl_employee', 'avatar_url')) {
                $table->string('avatar_url')->nullable()->after('full_name');
            }
            if (!Schema::connection('mysql_master')->hasColumn('tbl_employee', 'jabatan')) {
                $table->string('jabatan')->nullable()->after('avatar_url');
            }

        });
    }

    public function down(): void
    {
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
            $table->dropColumn(['avatar_url', 'jabatan']);
        });
    }
};
