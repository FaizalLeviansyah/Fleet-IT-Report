<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('vessel_id')->constrained('asset_categories')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->after('category_id')->constrained('asset_locations')->nullOnDelete();

            // Kolom Tambahan ala GLPI
            $table->string('manufacturer')->nullable()->after('asset_name');     // Dell, HP, ASUS
            $table->string('model')->nullable()->after('manufacturer');          // Latitude 5400, Mini PC PN50
            $table->string('contact_person')->nullable()->after('current_user'); // Penanggung jawab fisik
            $table->string('group_name')->nullable();                            // Department / Team
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Hapus relasi foreign key terlebih dahulu
            $table->dropForeign(['category_id']);
            $table->dropForeign(['location_id']);

            // Lalu hapus kolomnya
            $table->dropColumn([
                'category_id',
                'location_id',
                'manufacturer',
                'model',
                'contact_person',
                'group_name'
            ]);
        });
    }
};
