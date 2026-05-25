<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CctvSyncSeeder extends Seeder
{
    public function run(): void
    {
        $basePath = public_path('storage/laporan-images');

        if (!File::exists($basePath)) {
            $this->command->warn('Folder laporan-images tidak ditemukan, skip sinkronisasi.');
            return;
        }

        $directories = File::directories($basePath);
        $insertedCount = 0;

        foreach ($directories as $dir) {
            $vesselFolderName = basename($dir);
            // Kembalikan nama MT._Queen_Majesty menjadi MT. Queen Majesty
            $vesselName = str_replace('_', ' ', $vesselFolderName);

            $dateFolders = File::directories($dir);
            foreach ($dateFolders as $dateDir) {
                $files = File::files($dateDir);
                foreach ($files as $file) {
                    $filename = $file->getFilename();

                    // Asumsi nama file dari Python: temp_CH-01_20260522.jpg atau mirip
                    // Kita buat channel random untuk testing jika formatnya tidak ketahuan
                    $channel = 'CH-0' . rand(1, 6);

                    $relativePath = 'laporan-images/' . $vesselFolderName . '/' . basename($dateDir) . '/' . $filename;

                    // Ambil waktu modifikasi file asli sebagai captured_at
                    $fileTime = Carbon::createFromTimestamp($file->getMTime());

                    DB::table('cctv_reports')->insert([
                        'vessel_name' => $vesselName,
                        'channel' => $channel,
                        'image_path' => $relativePath,
                        'captured_at' => $fileTime,
                        'created_at' => $fileTime,
                        'updated_at' => $fileTime,
                    ]);
                    $insertedCount++;
                }
            }
        }
        $this->command->info("✅ Berhasil mensinkronkan $insertedCount foto lama ke Database!");
    }
}
