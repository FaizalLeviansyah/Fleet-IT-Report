<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // 1. INFORMASI DASAR TIKET
            $table->string('name'); // Judul Tiket
            $table->longText('content'); // Deskripsi Tiket (Rich Text)

            // Tipe Tiket: 1 = Incident (Gangguan), 2 = Request (Permintaan Layanan)
            $table->tinyInteger('type')->default(1);

            // Kategori ITIL (Nanti kita buat tabelnya di fase selanjutnya)
            $table->unsignedBigInteger('itilcategories_id')->nullable();

            // 2. STATUS & MATRIKS ITIL (Skala 1 = Sangat Rendah, 5 = Sangat Tinggi)
            $table->tinyInteger('status')->default(1); // 1:New, 2:Assigned, 3:Planned, 4:Pending, 5:Solved, 6:Closed
            $table->tinyInteger('urgency')->default(3); // Default: Medium (3)
            $table->tinyInteger('impact')->default(3);  // Default: Medium (3)
            $table->tinyInteger('priority')->default(3); // Dihitung otomatis oleh Model!

            // 3. AKTOR (Requester, Observer, Assigned Tech)
            // Asumsi tabel user Anda bernama 'users'
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('observer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();

            // 4. SLA TIMERS (Target Waktu Penyelesaian)
            $table->dateTime('time_to_own')->nullable(); // Batas waktu tiket harus direspon/diambil teknisi
            $table->dateTime('time_to_resolve')->nullable(); // Batas waktu tiket harus diselesaikan (Solved)

            $table->timestamps();
            $table->softDeletes(); // Fitur tong sampah (Trash/Recycle Bin) ala GLPI
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
