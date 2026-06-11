<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AssetSyncController extends Controller
{
    public function sync(Request $request)
    {
        try {
            // 1. Validasi Payload dari PowerShell
            $data = $request->validate([
                'hostname'     => 'required|string',
                'ip_address'   => 'nullable|string',
                'mac_address'  => 'required|string',
                'os_version'   => 'nullable|string',
                'cpu_model'    => 'nullable|string',
                'total_ram'    => 'nullable|string',
                'current_user' => 'nullable|string',
            ]);

            // 2. Cari Aset Berdasarkan MAC Address (Paling Akurat) atau Hostname
            $asset = Asset::where('mac_address', $data['mac_address'])
                          ->orWhere('asset_name', $data['hostname'])
                          ->first();

            if ($asset) {
                // 3a. Jika Aset Sudah Ada -> UPDATE DATANYA
                $asset->update([
                    'ip_address'   => $data['ip_address'],
                    'os_version'   => $data['os_version'],
                    'cpu_model'    => $data['cpu_model'],
                    'total_ram'    => $data['total_ram'],
                    'current_user' => $data['current_user'],
                    'last_seen'    => Carbon::now(),
                    'status'       => 'Active', // Nyalakan indikator hijau di Portal
                ]);
                $msg = 'Asset updated successfully';
            } else {
                // 3b. Jika Aset Baru -> REGISTER KE DATABASE
                Asset::create([
                    'asset_name'   => $data['hostname'],
                    'asset_type'   => 'Laptop/PC',
                    'mac_address'  => $data['mac_address'],
                    'ip_address'   => $data['ip_address'],
                    'os_version'   => $data['os_version'],
                    'cpu_model'    => $data['cpu_model'],
                    'total_ram'    => $data['total_ram'],
                    'current_user' => $data['current_user'],
                    'last_seen'    => Carbon::now(),
                    'status'       => 'Active',
                ]);
                $msg = 'New asset registered successfully';
            }

            return response()->json(['status' => 'success', 'message' => $msg], 200);

        } catch (\Exception $e) {
            Log::error('Amarin Agent Sync Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}