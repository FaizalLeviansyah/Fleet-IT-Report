<?php

namespace App\Http\Controllers;

use App\Models\IncidentTicket;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asset;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data tiket beserta relasinya, urutkan dari yang terbaru
        $tickets = IncidentTicket::with(['requester', 'assignee', 'asset'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        // Ambil data untuk dropdown peminta, kategori, aset tertaut, dll.
        $users = User::all();
        $assets = Asset::all();
        // Anda mungkin perlu memuat kategori tiket dari database juga

        return view('tickets.create', compact('users', 'assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_id' => 'required|exists:users,id',
            'observer_id' => 'nullable|exists:users,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:New,Processing,Solved,Withdrawn',
            'priority' => 'required|in:Low,Medium,High,Critical',
            'category' => 'required|string|max:255',
            'asset_id' => 'nullable|exists:assets,id',
        ]);

        // LOGIKA ANALITIS: State Machine (Ubah Status Berdasarkan User Ditugaskan)
        $status = $request->status;
        if ($request->assigned_to && $status === 'New') {
            $status = 'Processing';
        }

        // Simpan Tiket Baru
        $ticket = IncidentTicket::create([
            'title' => $request->title,
            'description' => $request->description,
            'requester_id' => $request->requester_id,
            'assigned_to' => $request->assigned_to,
            'status' => $status,
            'priority' => $request->priority,
            'category' => $request->category,
            'asset_id' => $request->asset_id,
            // solved_at akan tetap null sampai tiket diselesaikan
        ]);

        return redirect()->route('tickets.index')->with('success', 'Tiket baru berhasil dibuat dengan nomor ' . $ticket->ticket_number);
    }

    public function show(IncidentTicket $ticket)
    {
        // Panggil tiket beserta relasi thread (balasan) dan asetnya
        $ticket->load(['requester', 'assignee', 'asset', 'threads.user']);
        return view('tickets.show', compact('ticket'));
    }

    public function storeThread(Request $request, IncidentTicket $ticket)
    {
        $request->validate([
            'type' => 'required|in:Reply,Task,Document,Solution',
            'content' => 'required|string',
        ]);

        // 1. Simpan Balasan / Task / Solusi ke tabel threads
        $ticket->threads()->create([
            'user_id' => auth()->id() ?? 1, // Fallback user ID 1 jika belum ada sistem login yang aktif
            'type' => $request->type,
            'content' => $request->content,
        ]);

        // 2. LOGIKA ANALITIS: State Machine (Perubahan Status Otomatis)
        if ($request->type === 'Solution') {
            // Jika teknisi memberikan SOLUSI, tiket OTOMATIS CLOSED & Argo Downtime Berhenti!
            $ticket->update([
                'status' => 'Solved',
                'resolved_at' => now()
            ]);
            return back()->with('success', 'Solusi berhasil diberikan. Tiket otomatis ditutup!');
        }
        elseif ($request->type === 'Task' && $ticket->status === 'New') {
            // Jika teknisi mulai membuat TASK, ubah status jadi PROCESSING
            $ticket->update(['status' => 'Processing']);
        }

        return back()->with('success', 'Update berhasil ditambahkan pada tiket.');
    }

    public function updateStatus(Request $request, IncidentTicket $ticket)
    {
        $request->validate(['status' => 'required|in:New,Processing,Solved,Withdrawn']);

        $data = ['status' => $request->status];

        // Hentikan argo jika ditarik atau diselesaikan manual
        if (in_array($request->status, ['Solved', 'Withdrawn'])) {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);
        return back()->with('success', 'Status tiket berhasil diubah menjadi ' . $request->status);
    }
}
