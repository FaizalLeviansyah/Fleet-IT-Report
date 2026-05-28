<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // --- 1. PASTIKAN KOLOM BARU KITA (day & tasks) BENAR-BENAR ADA ---
        Schema::table('personal_actual_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('personal_actual_tasks', 'day')) {
                $table->string('day')->nullable();
            }
            if (!Schema::hasColumn('personal_actual_tasks', 'tasks')) {
                $table->json('tasks')->nullable();
            }
        });

        Schema::table('personal_planned_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('personal_planned_tasks', 'day')) {
                $table->string('day')->nullable();
            }
            if (!Schema::hasColumn('personal_planned_tasks', 'tasks')) {
                $table->json('tasks')->nullable();
            }
        });

        // --- 2. CEK DULU SEBELUM MENGUBAH KOLOM LAMA ---

        // Tabel Actual Tasks
        if (Schema::hasColumn('personal_actual_tasks', 'task_name')) {
            DB::statement("ALTER TABLE personal_actual_tasks MODIFY task_name VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('personal_actual_tasks', 'result')) {
            DB::statement("ALTER TABLE personal_actual_tasks MODIFY result VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('personal_actual_tasks', 'status')) {
            DB::statement("ALTER TABLE personal_actual_tasks MODIFY status VARCHAR(255) NULL");
        }

        // Tabel Planned Tasks
        if (Schema::hasColumn('personal_planned_tasks', 'plan_name')) {
            DB::statement("ALTER TABLE personal_planned_tasks MODIFY plan_name VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('personal_planned_tasks', 'target')) {
            DB::statement("ALTER TABLE personal_planned_tasks MODIFY target VARCHAR(255) NULL");
        }
        if (Schema::hasColumn('personal_planned_tasks', 'priority')) {
            DB::statement("ALTER TABLE personal_planned_tasks MODIFY priority VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        // Dibiarkan kosong agar aman saat rollback
    }
};
