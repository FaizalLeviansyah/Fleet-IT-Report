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
        
        // 🚨 FIX 1: Ubah employee_id menjadi id agar sesuai dengan User Biasa
        $myTickets = Ticket::where('requestor_id', $user->id)->get();
        
        $activeTickets = $myTickets->whereIn('status', [1, 2, 3, 4])->count();
        $resolvedTickets = $myTickets->whereIn('status', [5, 6])->count();
        $myAssets = 0; // Ganti dengan logic count Asset jika tabel sudah terhubung
        
        $recentTickets = Ticket::where('requestor_id', $user->id)
                               ->latest()
                               ->take(5)
                               ->get();

        return view('portal.dashboard', compact('activeTickets', 'resolvedTickets', 'myAssets', 'recentTickets'));
    }

    public function profile()
    {
        return view('portal.profile'); // Kita akan buat file ini selanjutnya
    }

    public function support()
    {
        // 🚨 FIX 2: Ubah employee_id menjadi id
        $tickets = Ticket::where('requestor_id', Auth::user()->id)->latest()->get();
        return view('portal.support', compact('tickets'));
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
            // 🚨 FIX 3: Saya samakan menjadi 'requestor_id' agar sinkron dengan query dashboard di atas
            'requestor_id' => Auth::id(), 
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