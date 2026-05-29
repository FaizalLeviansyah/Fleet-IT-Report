<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Memaksa MySQL mengizinkan kolom SLA ini untuk kosong (NULL)
        DB::statement('ALTER TABLE tickets MODIFY time_to_own DATETIME NULL');
        DB::statement('ALTER TABLE tickets MODIFY time_to_resolve DATETIME NULL');
    }

    public function down(): void
    {
        // Kosongkan
    }
};
