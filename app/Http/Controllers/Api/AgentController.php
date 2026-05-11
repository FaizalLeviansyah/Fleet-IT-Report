<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetSoftware;

class AgentController extends Controller
{
    public function syncData(Request $request)
    {
        // 1. Tangkap data JSON dari PC Kapal
        $uuid = $request->input('hardware_uuid');
        $deviceName = $request->input('device_name');

        // Keamanan dasar (Tolak jika tidak ada UUID)
        if (!$uuid) return response()->json(['status' => 'error', 'message' => 'Missing UUID'], 400);

        // 2. Cari aset ini di database, atau buat baru (Auto-Discovery)
        $asset = Asset::firstOrCreate(
            ['hardware_uuid' => $uuid],
            [
                'asset_name' => $deviceName,
                'asset_type' => 'PC/Laptop',
                'vessel_id' => 1, // Default ke kapal tertentu, nanti bisa diset dari agen
                'status' => 'Active'
            ]
        );

        // 3. Update Spesifikasi & Waktu Online (Heartbeat)
        $asset->update([
            'os_version' => $request->input('os_version'),
            'cpu_model' => $request->input('cpu_model'),
            'total_ram' => $request->input('total_ram'),
            'disk_space' => $request->input('disk_space'),
            'last_seen' => now(),
        ]);

        // 4. Update Daftar Software (Hapus yang lama, timpa yang baru terdeteksi)
        if ($request->has('software_list') && is_array($request->software_list)) {
            $asset->software()->delete(); // Bersihkan list lama

            $softwareData = [];
            foreach ($request->software_list as $soft) {
                if (!empty($soft['name'])) {
                    $softwareData[] = [
                        'asset_id' => $asset->id,
                        'software_name' => $soft['name'],
                        'version' => $soft['version'] ?? null,
                        'publisher' => $soft['publisher'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            // Masukkan secara massal agar cepat
            AssetSoftware::insert($softwareData);
        }

        return response()->json(['status' => 'success', 'message' => 'Amarin Sentinel Sync OK']);
    }
}
