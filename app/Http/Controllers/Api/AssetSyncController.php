<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class AssetSyncController extends Controller
{
    public function sync(Request $request)
    {
        $secretToken = 'Amarin-ITSM-Super-Secret-Token-2026';
        if ($request->header('X-Agent-Token') !== $secretToken) {
            return response()->json(['error' => 'Akses Ditolak! Agen Ilegal.'], 403);
        }

        $data = $request->validate([
            'mac_address' => 'required|string',
            'serial_number' => 'nullable|string',
            'computer_name' => 'required|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'os_version' => 'nullable|string',
            'cpu_model' => 'nullable|string',
            'ram_gb' => 'nullable|numeric',
            'storage_gb' => 'nullable|numeric',
            'ip_address' => 'nullable|string',
            'current_user' => 'nullable|string',
            'last_boot_time' => 'nullable|string',

            // 👇 DATA METRIK & JSON BARU 👇
            'cpu_usage' => 'nullable|numeric',
            'ram_usage' => 'nullable|numeric',
            'disk_usage' => 'nullable|numeric',
            'software_list' => 'nullable|array',
        ]);

        $asset = Asset::updateOrCreate(
            ['mac_address' => $data['mac_address']],
            [
                'asset_type' => 'PC / Laptop',
                'asset_name' => $data['computer_name'],
                'manufacturer' => $data['manufacturer'],
                'model' => $data['model'],
                'serial_number' => $data['serial_number'],
                'ip_address' => $data['ip_address'],
                'os_version' => $data['os_version'],
                'cpu_model' => $data['cpu_model'],
                'total_ram' => $data['ram_gb'] ? $data['ram_gb'] . ' GB' : null,
                'disk_space' => $data['storage_gb'] ? $data['storage_gb'] . ' GB' : null,
                'current_user' => $data['current_user'],
                'last_boot_time' => $data['last_boot_time'],

                // 👇 SIMPAN DATA METRIK & JSON BARU 👇
                'cpu_usage' => $data['cpu_usage'],
                'ram_usage' => $data['ram_usage'],
                'disk_usage' => $data['disk_usage'],
                'software_list' => $data['software_list'], // Disimpan otomatis sebagai JSON oleh Laravel

                'last_seen' => now(),
                'status' => 'Active',
            ]
        );

        return response()->json(['message' => 'Data Aset & Metrik Sukses Disinkronisasi!', 'asset_id' => $asset->id], 200);
    }
}
