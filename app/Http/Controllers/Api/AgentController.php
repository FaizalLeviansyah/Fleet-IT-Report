<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;

class AgentController extends Controller
{
    public function syncData(Request $request)
    {
        $uuid = $request->input('hardware_uuid');
        $deviceName = $request->input('device_name');

        if (!$uuid) return response()->json(['status' => 'error', 'message' => 'Missing UUID'], 400);

        $asset = Asset::firstOrCreate(
            ['hardware_uuid' => $uuid],
            [
                'asset_name' => $deviceName,
                'asset_type' => 'PC/Laptop',
                'vessel_id' => 1,
                'status' => 'Active'
            ]
        );

        // Update semua data termasuk IP Address dan JSON Software dalam 1 kali tembak!
        $asset->update([
            'ip_address' => $request->input('ip_address'),
            'os_version' => $request->input('os_version'),
            'cpu_model' => $request->input('cpu_model'),
            'total_ram' => $request->input('total_ram'),
            'disk_space' => $request->input('disk_space'),
            'software_list' => $request->input('software_list'), // Langsung simpan JSON-nya
            'last_seen' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Amarin Sentinel Sync OK']);
    }
}
