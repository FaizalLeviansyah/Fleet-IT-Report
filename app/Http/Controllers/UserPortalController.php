<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\KnowledgeBase;
use Illuminate\Support\Facades\Auth;

class UserPortalController extends Controller
{
    // 1. Halaman Beranda (Menampilkan statistik & tiket milik user)
    public function dashboard()
    {
        $user = Auth::user();
        $tickets = Ticket::where('requester_id', $user->id)->latest()->get();

        $activeCount = $tickets->whereIn('status', [1, 2, 3, 4])->count();
        $solvedCount = $tickets->whereIn('status', [5, 6])->count();

        return view('portal.dashboard', compact('tickets', 'activeCount', 'solvedCount'));
    }

    // 2. Halaman Form Buat Tiket
    public function createTicket()
    {
        return view('portal.create-ticket');
    }

    // 3. Proses Simpan Tiket ke Database
    public function storeTicket(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|integer'
        ]);

        Ticket::create([
            'ticket_number' => 'INC-' . date('Ymd') . '-' . rand(1000, 9999),
            'requester_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1, // Status: New
            'priority' => $request->priority,
            // Jika ada kolom created_by, buka comment di bawah:
            // 'created_by' => Auth::id(),
        ]);

        return redirect()->route('portal.dashboard')->with('success', 'Tiket berhasil dibuat! Tim IT akan segera memprosesnya.');
    }

    // 4. Halaman Knowledge Base (SOP)
    public function kb()
    {
        // Asumsi kolom status di tabel KnowledgeBase Anda adalah 'status'
        $articles = KnowledgeBase::latest()->get();
        return view('portal.kb', compact('articles'));
    }
}
