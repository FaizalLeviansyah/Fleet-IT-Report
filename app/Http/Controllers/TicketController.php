<?php

namespace App\Http\Controllers;

use App\Models\IncidentTicket;
use App\Models\WeeklyReport;
use App\Models\User;
use App\Models\Asset;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = IncidentTicket::with(['requester', 'assignee', 'asset'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $users = User::all();
        $assets = Asset::all();
        return view('tickets.create', compact('users', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_id' => 'required|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:New,Processing,Solved,Withdrawn',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'category' => 'required|string|max:255',
            'asset_id' => 'nullable|exists:assets,id',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $status = $request->status;
        if ($request->assigned_to && $status === 'New') {
            $status = 'Processing';
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets', 'public');
        }

        $ticket = IncidentTicket::create([
            'title' => $request->title,
            'description' => $request->description,
            'attachment' => $attachmentPath,
            'requester_id' => $request->requester_id,
            'assigned_to' => $request->assigned_to,
            'status' => $status,
            'priority' => $request->priority,
            'category' => $request->category,
            'asset_id' => $request->asset_id,
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket berhasil dibuat: ' . $ticket->ticket_number);
    }

    public function show(IncidentTicket $ticket)
    {
        $ticket->load(['requester', 'assignee', 'asset', 'threads.user']);
        return view('tickets.show', compact('ticket'));
    }

    public function storeThread(Request $request, IncidentTicket $ticket)
    {
        $request->validate([
            'type' => 'required|in:Reply,Task,Document,Solution',
            'content' => 'required|string',
        ]);

        $ticket->threads()->create([
            'user_id' => auth()->id() ?? 1,
            'type' => $request->type,
            'content' => $request->content,
        ]);

        if ($request->type === 'Solution') {
            $ticket->update([
                'status' => 'Solved',
                'resolved_at' => now()
            ]);

            // THE ULTIMATE BRIDGE: Panggil fungsi pemotong Uptime!
            $this->applyDowntimeToWeeklyReport($ticket);

            return back()->with('success', 'Solusi berhasil diberikan. Tiket otomatis ditutup & Uptime Laporan Mingguan telah dipotong!');
        }
        elseif ($request->type === 'Task' && $ticket->status === 'New') {
            $ticket->update(['status' => 'Processing']);
        }

        return back()->with('success', 'Update berhasil ditambahkan pada tiket.');
    }

    public function updateStatus(Request $request, IncidentTicket $ticket)
    {
        $request->validate(['status' => 'required|in:New,Processing,Solved,Withdrawn']);

        $data = ['status' => $request->status];

        if (in_array($request->status, ['Solved', 'Withdrawn'])) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        // Jika ditutup manual dari tombol status
        if ($request->status === 'Solved') {
            $this->applyDowntimeToWeeklyReport($ticket);
        }

        return back()->with('success', 'Status tiket berhasil diubah menjadi ' . $request->status);
    }

    /**
     * ==============================================================
     * THE DOWNTIME CALCULATOR ENGINE
     * ==============================================================
     */
    private function applyDowntimeToWeeklyReport(IncidentTicket $ticket)
    {
        // 1. Cek apakah tiket ini terhubung ke Aset dan Kapal
        if (!$ticket->asset_id || !$ticket->asset->vessel_id) return;

        $vesselId = $ticket->asset->vessel_id;

        // 2. Hitung Downtime (Durasi tiket terbuka sampai ditutup)
        // Kita ubah ke Float (Jam desimal) agar akurat, misal 1.5 Jam
        $downtimeHoursFloat = $ticket->created_at->diffInMinutes($ticket->resolved_at) / 60;

        // 3. Cari Laporan Mingguan minggu ini untuk kapal tersebut
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        // Jika belum ada laporan sama sekali minggu ini, buat draft baru otomatis
        $report = WeeklyReport::firstOrCreate(
            ['vessel_id' => $vesselId, 'report_date' => $startOfWeek],
            ['status' => 1, 'uptime_percentage' => 100, 'incident_list' => '']
        );

        // 4. Kalkulasi Pemotongan Uptime (1 Minggu = 168 Jam)
        $totalWeekHours = 168;
        $deductionPercentage = ($downtimeHoursFloat / $totalWeekHours) * 100;

        // Uptime baru = Uptime lama dikurangi persentase mati (minimal 0%)
        $newUptime = max(0, $report->uptime_percentage - $deductionPercentage);

        // 5. Rakit Teks Riwayat Insiden Otomatis
        $incidentText = sprintf("[%s] %s (Downtime: %.1f Jam)\n", $ticket->ticket_number, $ticket->title, $downtimeHoursFloat);
        $newIncidentList = $report->incident_list ? $report->incident_list . "\n" . $incidentText : $incidentText;

        // 6. Simpan kembali ke Database Laporan Mingguan
        $report->update([
            'uptime_percentage' => round($newUptime, 1),
            'incident_list' => $newIncidentList
        ]);
    }
}
