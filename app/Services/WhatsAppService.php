<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Fungsi utama untuk mengirim pesan WA.
     * Nanti tinggal sesuaikan URL API dan Header dari Mas Hendri.
     */
    public static function sendMessage($phone, $message)
    {
        // Hilangkan angka 0 di depan dan ganti dengan 62 (Standar WA)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        try {
            // =========================================================
            // 🚨 AREA API MAS HENDRI (Ubah bagian ini nanti) 🚨
            // =========================================================
            $apiUrl = env('WA_API_URL', 'https://api.wabot-amarin.com/send');
            $apiKey = env('WA_API_KEY', 'dummy-key-123');

            // Contoh payload (sesuaikan dengan format dokumentasi API Mas Hendri)
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post($apiUrl, [
                'number'  => $phone,
                'message' => $message,
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            // Catat ke log jika API gagal/down agar tidak membuat aplikasi error
            Log::error('WA API Failed: ' . $e->getMessage());
            return false;
        }
    }
}