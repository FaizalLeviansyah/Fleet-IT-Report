<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Models\WeeklyReport;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; // TAMBAHKAN INI UNTUK PDF

class ReportController extends Controller
{
    public function index()
    {
        $vessels = Vessel::all();
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $reportsThisWeek = WeeklyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->get();

        $vesselReports = $vessels->map(function ($vessel) use ($reportsThisWeek) {
            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            return (object) [
                'vessel' => $vessel,
                'report' => $report,
                'status' => $report ? $report->status : 0
            ];
        });

        return view('reports.index', compact('vesselReports'));
    }

    public function store(Request $request)
    {
        $status = $request->input('action_type') === 'draft' ? 1 : 3;

        WeeklyReport::updateOrCreate(
            [
                'vessel_id' => $request->vessel_id,
                'report_date' => $request->report_date,
                'status' => 1
            ],
            [
                'employee_id' => Auth::id(),
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

        $pesan = $status === 1 ? 'Laporan berhasil disimpan sebagai DRAFT.' : 'Laporan FINAL berhasil disubmit dan dikunci.';
        return redirect()->route('reports.index')->with('success', $pesan);
    }

    // FUNGSI BARU UNTUK DOWNLOAD PDF
    public function downloadPdf($id)
    {
        // Cari laporan berdasarkan ID, beserta data kapal dan pembuatnya
        $report = WeeklyReport::with(['vessel'])->findOrFail($id);

        // Render file view 'reports.pdf' menjadi dokumen PDF
        $pdf = Pdf::loadView('reports.pdf', compact('report'));

        // Atur ukuran kertas (A4)
        $pdf->setPaper('A4', 'portrait');

        // Nama file PDF dinamis (Misal: IT_Report_SOVIANA_20260424.pdf)
        $fileName = 'IT_Report_' . str_replace(' ', '_', $report->vessel->vessel_name) . '_' . Carbon::parse($report->report_date)->format('Ymd') . '.pdf';

        return $pdf->download($fileName);
        // (Atau gunakan $pdf->stream($fileName) jika ingin PDF-nya terbuka di browser dulu tanpa langsung terdownload)
    }

    public function history()
    {
        return view('reports.history');
    }
}
