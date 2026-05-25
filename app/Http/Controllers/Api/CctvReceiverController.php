<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CctvReceiverController extends Controller
{
    public function receive(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string',
            'label' => 'required|string',
            'snapshot' => 'required|file|mimes:jpeg,png,jpg',
        ]);

        try {
            $file = $request->file('snapshot');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // 1. FORMAT FOLDER (Persis Milik Farhan)
            $vesselFolder = str_replace(' ', '_', $request->lokasi);
            $tanggal = $request->folder_target ?? date('Y-m-d');
            $channel = $request->label; // AJG, BRT, dll

            // 2. SIMPAN KE FOLDER 3 TINGKAT: laporan-images/Kapal/Tanggal/Channel/
            $path = $file->storeAs("laporan-images/{$vesselFolder}/{$tanggal}/{$channel}", $filename, 'public');

            // 3. CATAT KE DATABASE
            DB::table('cctv_reports')->insert([
                'vessel_name' => $request->lokasi,
                'channel' => $channel,
                'image_path' => $path,
                'captured_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => '✅ Snapshot sukses masuk ke Folder & Database!'], 200);

        } catch (\Exception $e) {
            Log::error('CCTV API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses gambar.'], 500);
        }
    }
}
