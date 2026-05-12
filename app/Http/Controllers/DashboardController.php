<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;
use App\Models\WeeklyReport;
use App\Models\Asset;
use App\Models\IncidentTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /* =========================================================
           BAGIAN 1: LOGIKA IT FLEET REPORTING (WEEKLY COMPLIANCE)
           ========================================================= */
        $vessels = Vessel::all();
        $totalVessels = $vessels->count();

        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $reportsThisWeek = WeeklyReport::whereBetween('report_date', [$startOfWeek, $endOfWeek])->get();

        // Personalized Warning (Levi vs Farhan)
        $myFirstName = explode(' ', Auth::user()->full_name ?? Auth::user()->name ?? 'IT')[0];
        $myPendingVessels = $vessels->filter(function($vessel) use ($myFirstName, $reportsThisWeek) {
            $isMine = stripos($vessel->pic_name, $myFirstName) !== false;
            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            $isComplete = $report && $report->status == 3;
            return $isMine && !$isComplete;
        });

        $draftCount = $reportsThisWeek->where('status', 1)->count();
        $incidentCount = $reportsThisWeek->filter(function($r) { return $r->vessel_status === 'DOWN' || !empty($r->incident_list); })->count();
        $avgUptime = number_format($reportsThisWeek->avg('uptime_percentage') ?? 100, 1);

        $vesselReports = $vessels->map(function ($vessel) use ($reportsThisWeek) {
            $report = $reportsThisWeek->where('vessel_id', $vessel->id)->first();
            return (object) ['vessel' => $vessel, 'status' => $report ? $report->status : 0];
        });

        $recentActivities = WeeklyReport::with('vessel')->orderBy('updated_at', 'desc')->take(4)->get();

        $chartLabels = []; $chartData = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->subWeeks($i)->endOfWeek()->format('Y-m-d');
            $weekAvg = WeeklyReport::whereBetween('report_date', [$start, $end])->avg('uptime_percentage') ?? 100;
            $chartLabels[] = "Minggu " . Carbon::now()->subWeeks($i)->weekOfYear;
            $chartData[] = round($weekAvg, 1);
        }

        /* =========================================================
           BAGIAN 2: LOGIKA ITSM & SENTINEL (LIVE OPERATIONS)
           ========================================================= */
        $activeTickets = IncidentTicket::with(['asset.vessel', 'requester'])
            ->whereIn('status', ['New', 'Processing'])
            ->latest()
            ->get();

        $totalAssets = Asset::count();
        $onlineAssets = Asset::where('last_seen', '>=', now()->subHours(2))->count();
        $offlineAssets = $totalAssets - $onlineAssets;

        $vesselsWithIssues = $activeTickets->pluck('asset.vessel_id')->filter()->unique()->count();
        $totalVesselCount = $totalVessels > 0 ? $totalVessels : 1;
        $fleetHealth = 100 - (($vesselsWithIssues / $totalVesselCount) * 100);

        return view('dashboard', compact(
            'totalVessels', 'draftCount', 'incidentCount', 'avgUptime',
            'vesselReports', 'recentActivities', 'chartLabels', 'chartData', 'myPendingVessels',
            'activeTickets', 'totalAssets', 'onlineAssets', 'offlineAssets', 'fleetHealth', 'vessels'
        ));
    }
}
