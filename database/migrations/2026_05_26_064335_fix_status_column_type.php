<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // 👈 PENTING: Gunakan Facade DB

return new class extends Migration
{
    public function up(): void
    {
        // Menggunakan SQL Murni (Bypass dbal)
        DB::statement("ALTER TABLE personal_it_reports MODIFY status VARCHAR(255) NULL");
    }

    public function down(): void
    {
        // Kembalikan ke integer jika perlu
        DB::statement("ALTER TABLE personal_it_reports MODIFY status INT NULL");
    }
};
