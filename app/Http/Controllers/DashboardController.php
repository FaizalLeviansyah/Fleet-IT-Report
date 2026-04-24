<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Models\WeeklyReport;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vessels = Vessel::all();
        $totalVessels = $vessels->count();

        // 1. Ambil Data Minggu Ini
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $reportsThisWeek = WeeklyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->get();

        // 2. Hitung Widget Dashboard
        $draftCount = $reportsThisWeek->where('status', 1)->count();

        // Insiden: Kapal DOWN atau kolom insiden terisi
        $incidentCount = $reportsThisWeek->filter(function($report) {
            return $report->vessel_status === 'DOWN' || !empty($report->incident_list);
        })->count();

        // Rata-rata Uptime (jika tidak ada data, set 100%)
        $avgUptime = $reportsThisWeek->avg('uptime_percentage');
        $avgUptime = $avgUptime ? number_format($avgUptime, 1) : 100;

        // 3. Data Tabel Monitoring (Read-Only)
        $vesselReports = $vessels->map(function ($vessel) use ($reportsThisWeek) {
            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            return (object) [
                'vessel' => $vessel,
                'status' => $report ? $report->status : 0
            ];
        });

        // 4. System Audit Trail (Ambil 3 laporan terakhir yang diedit)
        $recentActivities = WeeklyReport::with('vessel')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        // 5. Data Chart (Trend 4 Minggu Terakhir)
        $chartLabels = [];
        $chartData = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->subWeeks($i)->endOfWeek()->format('Y-m-d');

            $weekAvg = WeeklyReport::whereBetween('report_date', [$start, $end])->avg('uptime_percentage') ?? 100;

            // Nama label di chart (Misal: "Minggu 12")
            $chartLabels[] = "Minggu " . Carbon::now()->subWeeks($i)->weekOfYear;
            $chartData[] = round($weekAvg, 1);
        }

        return view('dashboard', compact(
            'totalVessels', 'draftCount', 'incidentCount', 'avgUptime',
            'vesselReports', 'recentActivities', 'chartLabels', 'chartData'
        ));
    }
}
