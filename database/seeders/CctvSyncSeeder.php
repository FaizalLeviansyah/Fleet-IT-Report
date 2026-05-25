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

        $vesselDirs = File::directories($basePath);
        $insertedCount = 0;

        foreach ($vesselDirs as $vesselDir) {
            $vesselFolderName = basename($vesselDir);
            $vesselName = str_replace('_', ' ', $vesselFolderName); // MT._Queen -> MT. Queen
            
            // Masuk ke tingkat 2 (Folder Tanggal)
            $dateDirs = File::directories($vesselDir);
            foreach ($dateDirs as $dateDir) {
                $dateFolderName = basename($dateDir); // Misal: 2026-04-16
                
                // MASUK KE TINGKAT 3 (Folder Channel: AJG, BRT) -> INI YANG TERLEWAT SEBELUMNYA!
                $channelDirs = File::directories($dateDir);
                foreach ($channelDirs as $channelDir) {
                    $channelName = basename($channelDir); // Akan terbaca 'AJG', 'BRT', dll
                    
                    // Akhirnya ambil file fotonya!
                    $files = File::files($channelDir);
                    foreach ($files as $file) {
                        $filename = $file->getFilename();
                        $relativePath = "laporan-images/{$vesselFolderName}/{$dateFolderName}/{$channelName}/{$filename}";
                        
                        // Gabungkan Tanggal dari Folder + Jam dari waktu file dibuat
                        $timeString = date('H:i:s', $file->getMTime());
                        $fileTime = Carbon::parse($dateFolderName . ' ' . $timeString);

                        DB::table('cctv_reports')->insert([
                            'vessel_name' => $vesselName,
                            'channel' => $channelName, // Murni terbaca dari folder (AJG/BRT)
                            'image_path' => $relativePath,
                            'captured_at' => $fileTime,
                            'created_at' => $fileTime,
                            'updated_at' => $fileTime,
                        ]);
                        $insertedCount++;
                    }
                }
            }
        }
        $this->command->info("✅ JENIUS! Berhasil mensinkronkan $insertedCount foto lama ke Database!");
    }
}