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
        // Ambil riwayat laporan kinerja khusus untuk user yang sedang login saja
        $reports = PersonalItReport::with(['actualTasks', 'plannedTasks'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('personal_reports.index', compact('reports'));
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
