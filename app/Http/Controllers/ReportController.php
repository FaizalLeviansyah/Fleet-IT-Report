<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Models\WeeklyReport;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        // Ambil semua armada
        $vessels = Vessel::all();

        // Cek draft/status laporan tiap kapal (Disimpan dalam array untuk dilempar ke View)
        // Saat ini kita pakai simulasi simpel dulu untuk view-nya
        return view('reports.index', compact('vessels'));
    }

    public function store(Request $request)
    {
        // 1. Tentukan status dari tombol yang ditekan
        $status = $request->input('action_type') === 'draft' ? 1 : 3;

        // 2. Simpan atau Update data ke Database
        WeeklyReport::updateOrCreate(
            [
                // Jika sudah ada laporan draft untuk kapal ini di hari ini, maka UPDATE
                'vessel_id' => $request->vessel_id,
                'report_date' => $request->report_date,
                'status' => 1 // Hanya update yang masih draft
            ],
            [
                // Kolom-kolom yang diisi/diupdate
                'employee_id' => Auth::id(), // ID dari akun master
                'vessel_status' => $request->vessel_status,
                'uptime_percentage' => $request->uptime_percentage,
                'sla_compliance' => $request->sla_compliance,
                'incident_list' => $request->incident_list,
                'root_cause' => $request->root_cause,
                'maintenance_type' => $request->maintenance_type,
                'preventive_maintenance' => $request->preventive_maintenance,
                'performance_trend' => $request->performance_trend,
                'risk_identification' => $request->risk_identification,
                'activity_log' => $request->activity_log,
                'inventory_tracking' => $request->inventory_tracking,
                'status' => $status
            ]
        );

        // 3. Tentukan pesan notifikasi
        $pesan = $status === 1
            ? 'Laporan berhasil disimpan sebagai DRAFT.'
            : 'Laporan FINAL berhasil disubmit dan dikunci.';

        return redirect()->route('reports.index')->with('success', $pesan);
    }

    public function history()
    {
        return view('reports.history');
    }
}
