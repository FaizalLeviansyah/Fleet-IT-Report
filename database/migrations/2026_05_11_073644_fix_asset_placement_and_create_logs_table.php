<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Perbaiki cacat logika penempatan di tabel assets
        Schema::table('assets', function (Blueprint $table) {
            $table->string('company_entity')->nullable()->after('asset_name'); // CTP, ASM, ACS
            // Buat vessel_id menjadi nullable (Karena aset bisa saja di kantor, bukan di kapal)
            $table->unsignedBigInteger('vessel_id')->nullable()->change();
        });

        // 2. Buat tabel Historical Log (Audit Trail)
        Schema::create('asset_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang ngedit?
            $table->string('action'); // Created, Updated
            $table->json('changes')->nullable(); // Simpan {field: {old: x, new: y}}
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_logs');
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('company_entity');
        });
    }
};
