<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalItReport;
use App\Models\PersonalActualTask;
use App\Models\PersonalPlannedTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PersonalReportController extends Controller
{
    public function index()
    {
        // 1. Ambil riwayat laporan kinerja
        $reports = PersonalItReport::with(['actualTasks', 'plannedTasks'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. LOGIKA AUTO-SYNC (Tarik data dari Laporan Armada minggu ini)
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');
        $myFirstName = explode(' ', Auth::user()->full_name ?? Auth::user()->name)[0] ?? 'IT';

        // Cari laporan armada minggu ini yang dikerjakan oleh user yang sedang login
        $myVesselReports = \App\Models\WeeklyReport::with('vessel')
            ->whereBetween('report_date', [$startOfWeek, $endOfWeek])
            ->whereHas('vessel', function($q) use ($myFirstName) {
                $q->where('pic_name', 'like', "%{$myFirstName}%");
            })->get();

        $autoSyncTasks = [];
        foreach ($myVesselReports as $vr) {
            // Tarik Insiden
            if (!empty($vr->incident_list)) {
                $autoSyncTasks[] = [
                    'date' => $vr->report_date,
                    'task' => 'Troubleshooting ' . $vr->vessel->vessel_name,
                    'result' => substr($vr->incident_list, 0, 50) . '...', // Potong teks agar rapi
                    'status' => 'Selesai'
                ];
            }
            // Tarik Maintenance
            if (!empty($vr->preventive_maintenance)) {
                $autoSyncTasks[] = [
                    'date' => $vr->report_date,
                    'task' => 'Maintenance ' . $vr->vessel->vessel_name,
                    'result' => substr($vr->preventive_maintenance, 0, 50) . '...',
                    'status' => 'Selesai'
                ];
            }
        }

        return view('personal_reports.index', compact('reports', 'autoSyncTasks'));
    }

    public function store(Request $request)
    {
        $status = $request->input('action_type') === 'draft' ? 1 : 3;
        $today = Carbon::now();
        $endDate = Carbon::parse($request->end_date); // Hari Jumat laporan tersebut

        // PERTAHANAN BACKEND CERDAS:
        // Jika status Final, DAN tanggal akhir laporan adalah minggu ini/masa depan,
        // DAN hari ini belum hari Jumat (masih Senin-Kamis) -> BLOKIR!
        if ($status === 3 && ($endDate->isFuture() || $today->isSameWeek($endDate))) {
            if ($today->dayOfWeek >= 1 && $today->dayOfWeek <= 4) {
                return redirect()->back()->with('error', 'Sistem Terkunci: Laporan minggu ini hanya boleh di-submit final pada hari Jumat.');
            }
        }

        $report = PersonalItReport::updateOrCreate(
            ['user_id' => Auth::id(), 'start_date' => $request->start_date],
            [
                'end_date' => $request->end_date,
                'status' => $status,
                'late_remark' => $request->late_remark // Simpan Alasan Terlambat
            ]
        );

        $report->actualTasks()->delete();
        $report->plannedTasks()->delete();

        if ($request->has('actual_task')) {
            foreach ($request->actual_task as $key => $taskName) {
                if (!empty($taskName)) {
                    PersonalActualTask::create([
                        'personal_it_report_id' => $report->id,
                        'task_date' => $request->actual_date[$key] ?? $today->format('Y-m-d'),
                        'task_name' => $taskName,
                        'result' => $request->actual_result[$key] ?? '-',
                        'status' => $request->actual_status[$key] ?? 'Selesai',
                        'notes' => $request->actual_notes[$key] ?? null,
                    ]);
                }
            }
        }

        if ($request->has('planned_task')) {
            foreach ($request->planned_task as $key => $planName) {
                if (!empty($planName)) {
                    PersonalPlannedTask::create([
                        'personal_it_report_id' => $report->id,
                        'plan_name' => $planName,
                        'target' => $request->planned_target[$key] ?? '-',
                        'priority' => $request->planned_priority[$key] ?? 'Sedang',
                        'deadline' => $request->planned_deadline[$key] ?? null,
                        'notes' => $request->planned_notes[$key] ?? null,
                    ]);
                }
            }
        }

        $pesan = $status === 1 ? 'Laporan Kinerja disimpan sebagai DRAFT.' : 'Laporan Kinerja FINAL berhasil disubmit.';
        return redirect()->route('personal.reports.index')->with('success', $pesan);
    }
}
