<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Menambahkan kolom ke database master
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
            // Kita cek dulu agar tidak error kalau kolomnya ternyata sudah terlanjur ada
            if (!Schema::connection('mysql_master')->hasColumn('tbl_employee', 'is_it_team')) {
                $table->boolean('is_it_team')->default(0)->after('employee_id');
            }
        });

        // 2. Otomatis set 3 orang awal sebagai Tim IT (Super Admin)
        DB::connection('mysql_master')->table('tbl_employee')
            ->whereIn('full_name', [
                'FAIZAL LEVIANSYAH',
                'FARHAN ARIF INDIARTO',
                'HENDRI SETIO PRAKOSO'
            ])
            ->update(['is_it_team' => 1]);
    }

    public function down(): void
    {
        // Fitur Rollback jika dibutuhkan
        Schema::connection('mysql_master')->table('tbl_employee', function (Blueprint $table) {
            if (Schema::connection('mysql_master')->hasColumn('tbl_employee', 'is_it_team')) {
                $table->dropColumn('is_it_team');
            }
        });
    }
};
