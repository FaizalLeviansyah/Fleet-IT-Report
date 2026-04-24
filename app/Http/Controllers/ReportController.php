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
    public function index(Request $request)
    {
        $vessels = Vessel::all();

        // 1. Ambil Tahun dari inputan (Default: Tahun Ini)
        $selectedYear = $request->input('year', \Carbon\Carbon::now()->year);

        // 2. Buat Mesin Kalender (1 Tahun = 52/53 Minggu, Dikelompokkan per Bulan)
        $calendar = [];
        $weeksInYear = \Carbon\Carbon::create($selectedYear)->isoWeeksInYear;

        for ($week = 1; $week <= $weeksInYear; $week++) {
            $date = \Carbon\Carbon::now()->setISODate($selectedYear, $week);
            $monthName = $date->translatedFormat('F'); // Contoh: "January", "February"

            // Simpan tanggal hari Jumat pada minggu tersebut sebagai target default form
            $targetFriday = $date->endOfWeek(\Carbon\Carbon::FRIDAY)->format('Y-m-d');
            $calendar[$monthName][$week] = $targetFriday;
        }

        // 3. Ambil Semua Laporan di Tahun Tersebut
        $reports = WeeklyReport::whereYear('report_date', $selectedYear)->get();

        // 4. Petakan laporan ke dalam Array [ID_KAPAL][MINGGU_KE] agar mudah diakses di View
        $reportMap = [];
        foreach ($reports as $report) {
            $weekNum = \Carbon\Carbon::parse($report->report_date)->isoWeek;
            $reportMap[$report->vessel_id][$weekNum] = $report;
        }

        return view('reports.index', compact('vessels', 'selectedYear', 'calendar', 'reportMap'));
    }

    public function store(Request $request)
    {
        $status = $request->input('action_type') === 'draft' ? 1 : 3;

        // Kita ubah patokan pencariannya HANYA menggunakan vessel_id dan status draft.
        // Ini mencegah duplikasi jika user mengubah tanggal laporan.
        WeeklyReport::updateOrCreate(
            [
                'vessel_id' => $request->vessel_id,
                'status' => 1 // Selalu cari yang masih DRAFT untuk ditimpa
            ],
            [
                'employee_id' => Auth::id(),
                // BULLETPROOF: Jika browser gagal kirim tanggal, paksa pakai tanggal hari ini
                'report_date' => $request->filled('report_date') ? $request->report_date : \Carbon\Carbon::now()->format('Y-m-d'),

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
                'late_remark' => $request->late_remark, // <--- TAMBAHKAN INI
                'status' => $status
            ]
        );

        $pesan = $status === 1 ? 'Laporan berhasil disimpan sebagai DRAFT.' : 'Laporan FINAL berhasil disubmit dan dikunci.';
        return redirect()->route('reports.index')->with('success', $pesan);
    }

    // FUNGSI BARU UNTUK DOWNLOAD PDF
    public function downloadPdf(Request $request, $id)
    {
        // Cari laporan beserta relasi vessel-nya
        $report = WeeklyReport::with(['vessel'])->findOrFail($id);

        // Render file view 'reports.pdf' menjadi PDF
        $pdf = Pdf::loadView('reports.pdf', compact('report'));

        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        // Penamaan file PDF
        $fileName = 'IT_Report_' . str_replace(' ', '_', $report->vessel->vessel_name) . '_' . Carbon::parse($report->report_date)->format('Ymd') . '.pdf';

        // Jika ada request ?download=1, maka paksa unduh. Jika tidak, tampilkan (stream)
        if ($request->query('download') == 1) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName);
    }

    public function history(Request $request)
    {
        // Ambil query pencarian jika ada
        $search = $request->input('search');

        // Ambil data laporan yang HANYA berstatus FINAL (status = 3)
        $query = WeeklyReport::with('vessel')->where('status', 3);

        if ($search) {
            $query->whereHas('vessel', function($q) use ($search) {
                $q->where('vessel_name', 'like', "%{$search}%");
            })->orWhere('report_date', 'like', "%{$search}%");
        }

        // Urutkan dari yang paling baru
        $historyReports = $query->orderBy('report_date', 'desc')->paginate(10);

        return view('reports.history', compact('historyReports', 'search'));
    }

}
