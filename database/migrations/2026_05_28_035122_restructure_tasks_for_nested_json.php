<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cek dan eksekusi untuk tabel ACTUAL TASKS
        if (Schema::hasColumn('personal_actual_tasks', 'task_description')) {
            Schema::table('personal_actual_tasks', function (Blueprint $table) {
                // Buang jika masih ada
                $table->dropColumn(['task_description', 'status', 'remarks']);
            });
        }

        if (!Schema::hasColumn('personal_actual_tasks', 'tasks')) {
            Schema::table('personal_actual_tasks', function (Blueprint $table) {
                // Tambahkan JSON kolom jika belum ada
                $table->json('tasks')->nullable();
            });
        }

        // 2. Cek dan eksekusi untuk tabel PLANNED TASKS
        if (Schema::hasColumn('personal_planned_tasks', 'task_description')) {
            Schema::table('personal_planned_tasks', function (Blueprint $table) {
                $table->dropColumn(['task_description']);
            });
        }

        if (!Schema::hasColumn('personal_planned_tasks', 'tasks')) {
            Schema::table('personal_planned_tasks', function (Blueprint $table) {
                $table->json('tasks')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu diisi untuk skenario ini agar aman
    }
};
