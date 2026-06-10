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
        
        // 🚨 FIX MUTLAK: Pakai 'requester_id' (huruf E) dan '$user->employee_id'
        $myTickets = Ticket::where('requester_id', $user->employee_id)->get();
        
        $activeTickets = $myTickets->whereIn('status', [1, 2, 3, 4])->count();
        $resolvedTickets = $myTickets->whereIn('status', [5, 6])->count();
        $myAssets = 0; // Ganti dengan logic count Asset jika tabel sudah terhubung
        
        $recentTickets = Ticket::where('requester_id', $user->employee_id)
                               ->latest()
                               ->take(5)
                               ->get();

        return view('portal.dashboard', compact('activeTickets', 'resolvedTickets', 'myAssets', 'recentTickets'));
    }

    public function profile()
    {
        return view('portal.profile'); 
    }

    public function support()
    {
        // 🚨 FIX MUTLAK: Pakai 'requester_id' dan 'employee_id'
        $tickets = Ticket::where('requester_id', Auth::user()->employee_id)->latest()->get();
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
            'requester_id' => Auth::user()->employee_id, // 🚨 FIX MUTLAK
            'name' => $request->name,
            'description' => $request->description,
            'status' => 1, // Status: New
            'priority' => $request->priority,
        ]);

        return redirect()->route('portal.dashboard')->with('success', 'Tiket berhasil dibuat! Tim IT akan segera memprosesnya.');
    }

    // 4. Halaman Knowledge Base (SOP)
    public function kb()
    {
        $articles = KnowledgeBase::latest()->get();
        return view('portal.kb', compact('articles'));
    }
}