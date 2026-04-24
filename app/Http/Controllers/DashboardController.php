<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Models\WeeklyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $vessels = Vessel::all();
        $totalVessels = $vessels->count();

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $reportsThisWeek = WeeklyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->get();

        // LOGIKA PERSONALIZED WARNING (LEVI VS FARHAN)
        // Ambil nama depan user yang sedang login (Misal: "Levi", "Farhan")
        $myFirstName = explode(' ', Auth::user()->full_name ?? Auth::user()->name)[0] ?? 'IT';

        // Saring: Mana kapal milik saya yang laporan minggu ini belum Final (Status 3)?
        $myPendingVessels = $vessels->filter(function($vessel) use ($myFirstName, $reportsThisWeek) {
            // Cek apakah nama PIC di database mengandung nama saya
            $isMine = stripos($vessel->pic_name, $myFirstName) !== false;

            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            $isComplete = $report && $report->status == 3;

            return $isMine && !$isComplete;
        });

        // (Sisanya sama seperti kode Dashboard Anda sebelumnya)
        $draftCount = $reportsThisWeek->where('status', 1)->count();
        $incidentCount = $reportsThisWeek->filter(function($r) { return $r->vessel_status === 'DOWN' || !empty($r->incident_list); })->count();
        $avgUptime = number_format($reportsThisWeek->avg('uptime_percentage') ?? 100, 1);

        $vesselReports = $vessels->map(function ($vessel) use ($reportsThisWeek) {
            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            return (object) ['vessel' => $vessel, 'status' => $report ? $report->status : 0];
        });

        $recentActivities = WeeklyReport::with('vessel')->orderBy('updated_at', 'desc')->take(3)->get();
        $chartLabels = []; $chartData = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->subWeeks($i)->endOfWeek()->format('Y-m-d');
            $weekAvg = WeeklyReport::whereBetween('report_date', [$start, $end])->avg('uptime_percentage') ?? 100;
            $chartLabels[] = "Minggu " . Carbon::now()->subWeeks($i)->weekOfYear;
            $chartData[] = round($weekAvg, 1);
        }

        // Jangan lupa sertakan 'myPendingVessels' di compact()
        return view('dashboard', compact(
            'totalVessels', 'draftCount', 'incidentCount', 'avgUptime',
            'vesselReports', 'recentActivities', 'chartLabels', 'chartData', 'myPendingVessels'
        ));
    }
}
