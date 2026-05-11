<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use Illuminate\Support\Facades\Log;

class PingNetworkDevices extends Command
{
    // Nama perintah yang akan dipanggil di terminal
    protected $signature = 'network:ping';

    // Deskripsi perintah
    protected $description = 'Melakukan ICMP Ping ke semua aset Non-PC untuk mendeteksi status Online/Offline';

    public function handle()
    {
        $this->info('Memulai Amarin Network Radar...');

        // Ambil semua aset yang PUNYA IP Address, TAPI BUKAN kategori 'Computers'
        $assets = Asset::whereNotNull('ip_address')
            ->whereHas('category', function($q) {
                $q->where('name', '!=', 'Computers');
            })->get();

        if ($assets->isEmpty()) {
            $this->warn('Tidak ada perangkat jaringan yang perlu di-ping.');
            return;
        }

        $onlineCount = 0;
        $offlineCount = 0;

        foreach ($assets as $asset) {
            $ip = trim($asset->ip_address);

            // Validasi format IP sederhana sebelum di-ping agar tidak error
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $this->error("IP tidak valid untuk {$asset->asset_name} ({$ip})");
                continue;
            }

            // Logika Ping Cerdas (Deteksi Windows vs Linux/Mac)
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows: -n 1 (1 paket), -w 1000 (timeout 1 detik)
                $pingCommand = "ping -n 1 -w 1000 " . escapeshellarg($ip);
            } else {
                // Linux/Mac: -c 1 (1 paket), -W 1 (timeout 1 detik)
                $pingCommand = "ping -c 1 -W 1 " . escapeshellarg($ip);
            }

            // Eksekusi ping diam-diam di background
            exec($pingCommand, $output, $resultCode);

            // Result Code 0 artinya sukses (Reply)
            if ($resultCode === 0) {
                // Update Last Seen!
                $asset->update(['last_seen' => now()]);
                $this->info("[$ip] ONLINE  -> {$asset->asset_name}");
                $onlineCount++;
            } else {
                $this->error("[$ip] OFFLINE -> {$asset->asset_name}");
                $offlineCount++;
            }
        }

        $this->info("Radar Selesai! Online: $onlineCount | Offline: $offlineCount");
    }
}
