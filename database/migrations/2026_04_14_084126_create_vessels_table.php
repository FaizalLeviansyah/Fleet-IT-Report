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
    Schema::create('vessels', function (Blueprint $table) {
        $table->id();
        $table->string('company_name'); // cth: PT ASL
        $table->string('vessel_name'); // cth: SOVIANA
        $table->string('pic_name'); // Levi atau Farhan
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
