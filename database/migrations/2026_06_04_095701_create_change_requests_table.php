<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Perubahan
            $table->longText('description'); // Penjelasan detail apa yang diubah
            $table->longText('justification')->nullable(); // Alasan KENAPA harus diubah (Business value)
            $table->longText('fallback_plan')->nullable(); // Rencana jika terjadi kegagalan (Rollback)

            // Relasi ke tabel pegawai (tanpa gembok constraint lintas database)
            $table->unsignedBigInteger('requester_id'); // IT yang mengajukan
            $table->unsignedBigInteger('manager_id')->nullable(); // IT Manager yang meng-approve (HENDRI)

            // Status Perubahan
            $table->tinyInteger('status')->default(1);
            // 1 = Draft, 2 = Pending Approval, 3 = Approved, 4 = Rejected, 5 = Implemented (Selesai)

            // Risiko Perubahan
            $table->enum('risk_level', ['Low', 'Medium', 'High', 'Critical'])->default('Low');

            $table->dateTime('planned_start_date')->nullable();
            $table->dateTime('planned_end_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('change_requests');
    }
};
