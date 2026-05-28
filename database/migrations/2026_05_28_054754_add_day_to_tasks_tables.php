<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom day di Actual Tasks
        if (!Schema::hasColumn('personal_actual_tasks', 'day')) {
            Schema::table('personal_actual_tasks', function (Blueprint $table) {
                $table->string('day')->nullable();
            });
        }

        // Tambah kolom day di Planned Tasks
        if (!Schema::hasColumn('personal_planned_tasks', 'day')) {
            Schema::table('personal_planned_tasks', function (Blueprint $table) {
                $table->string('day')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu diisi
    }
};
