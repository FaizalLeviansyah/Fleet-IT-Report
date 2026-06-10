<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\KnowledgeBase;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;

class UserPortalController extends Controller
{
    // =========================================================================
    // 1. HALAMAN BERANDA (DASHBOARD)
    // =========================================================================
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ambil tiket milik user
        $myTickets = Ticket::where('requester_id', $user->employee_id)->get();
        
        $activeTickets = $myTickets->whereIn('status', [1, 2, 3, 4])->count();
        $resolvedTickets = $myTickets->whereIn('status', [5, 6])->count();
        
        // 🚨 FIX: Hitung aset berdasarkan kolom current_user atau contact_person
        $myAssets = Asset::where('current_user', $user->full_name)
                         ->orWhere('contact_person', $user->full_name)
                         ->count(); 
        
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
        $tickets = Ticket::where('requester_id', Auth::user()->employee_id)->latest()->get();
        return view('portal.support', compact('tickets'));
    }

    // =========================================================================
    // 2. HALAMAN FORM BUAT TIKET
    // =========================================================================
    public function createTicket()
    {
        $user = Auth::user();

        // 1. Ambil Aset Pribadi (Berdasarkan Nama)
        $myAssets = Asset::where('current_user', $user->full_name)
                         ->orWhere('contact_person', $user->full_name)
                         ->get();

        // 2. Ambil Aset Umum (Printer, Scanner, Network, dll yang tidak dipegang perorangan)
        // Asumsi: Aset umum kolom current_user-nya kosong (null)
        $generalAssets = Asset::whereNull('current_user')
                              ->whereNull('contact_person')
                              ->get();

        return view('portal.create-ticket', compact('myAssets', 'generalAssets'));
    }

    // =========================================================================
    // 3. PROSES SIMPAN TIKET KE DATABASE
    // =========================================================================
    public function storeTicket(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'priority' => 'required|integer',
            'description' => 'required|string',
            'asset_id' => 'nullable|exists:assets,id' // Validasi ID aset jika dipilih
        ]);

        Ticket::create([
            'ticket_number' => 'INC-' . date('Ymd') . '-' . rand(1000, 9999),
            'requester_id' => Auth::user()->employee_id, 
            'name' => $request->name,
            'type' => $request->type, // Kategori (Incident / Service Request)
            'priority' => $request->priority,
            'description' => $request->description,
            // Jika ada kolom asset_id di tabel tiket, nyalakan ini:
            // 'asset_id' => $request->asset_id,
            'status' => 1, // Status: New (Menunggu Dispatcher)
            'assigned_to' => null, 
        ]);

        return redirect()->route('portal.dashboard')
            ->with('success', 'Tiket berhasil dikirim! Menunggu assign dari Supervisor IT.');
    }

    // =========================================================================
    // 4. KNOWLEDGE BASE
    // =========================================================================
    public function kb()
    {
        $articles = KnowledgeBase::latest()->get();
        return view('portal.kb', compact('articles'));
    }

    // =========================================================================
    // 5. VISUALISASI ASET PEGAWAI (ITAM)
    // =========================================================================
    public function myAssets()
    {
        $user = Auth::user();

        // 🚨 FIX: Ambil data aset berdasarkan kolom current_user
        $assets = Asset::with('category') // Pastikan relasi category ada di model Asset
                       ->where('current_user', $user->full_name)
                       ->orWhere('contact_person', $user->full_name)
                       ->get();

        return view('portal.my-assets', compact('assets'));
    }

    // =========================================================================
    // 6. FITUR FOLLOW-UP & APPROVAL TIKET
    // =========================================================================
    public function showTicket($id)
    {
        $user = Auth::user();
        
        $ticket = Ticket::with(['requester', 'technician', 'followups.user'])
            ->where('id', $id)
            ->where('requester_id', $user->employee_id)
            ->firstOrFail();

        return view('portal.show-ticket', compact('ticket'));
    }

    public function replyTicket(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120', 
        ]);

        $ticket = Ticket::where('id', $id)->where('requester_id', Auth::user()->employee_id)->firstOrFail();

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket-attachments', 'public');
        }

        \App\Models\TicketFollowup::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->employee_id, 
            'message' => $request->message,
            'attachment' => $attachmentPath,
        ]);

        return redirect()->back()->with('success', 'Pesan berhasil dikirim.');
    }

    public function approveTicket($id)
    {
        $ticket = Ticket::where('id', $id)->where('requester_id', Auth::user()->employee_id)->firstOrFail();
        
        $ticket->update(['status' => 6]);

        \App\Models\TicketFollowup::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->employee_id,
            'message' => '✅ TIKET DISETUJUI DAN DITUTUP OLEH REQUESTER.',
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Tiket telah disetujui dan ditutup.');
    }
}