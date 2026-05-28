<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus task_name dari Actual Tasks jika ada
        Schema::table('personal_actual_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('personal_actual_tasks', 'task_name')) {
                $table->dropColumn('task_name');
            }
        });

        // Hapus task_name dari Planned Tasks jika ada
        Schema::table('personal_planned_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('personal_planned_tasks', 'task_name')) {
                $table->dropColumn('task_name');
            }
        });
    }

    public function down(): void
    {
        // Tidak perlu diisi
    }
};
