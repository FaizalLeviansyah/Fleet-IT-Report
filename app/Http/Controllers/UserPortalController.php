<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketFollowup;
use Illuminate\Support\Facades\Storage;
use App\Models\KnowledgeBase;
use App\Models\Asset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    // =========================================================================
    // UPDATE PASSWORD PEGAWAI
    // =========================================================================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed', // Pastikan confirm password sesuai
        ]);

        $user = Auth::user();

        // Cek apakah password lama yang diketikkan cocok dengan database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok dengan sistem.']);
        }

        // Simpan password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password Anda berhasil diperbarui!');
    }

    // =========================================================================
    // UPDATE FOTO PROFIL PEGAWAI
    // =========================================================================
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru ke folder storage/app/public/profile-photos
            $path = $request->file('photo')->store('profile-photos', 'public');
            
            $user->update([
                'profile_photo_path' => $path
            ]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
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
        // Aset Pribadi
        $myAssets = Asset::where('current_user', $user->full_name)->orWhere('contact_person', $user->full_name)->get();
        // Aset Umum (Asumsi tidak ada pemilik spesifik)
        $generalAssets = Asset::whereNull('current_user')->whereNull('contact_person')->get();

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

    // =========================================================================
    // FUNGSI BALAS PESAN TIKET (DARI USER KE TIM IT)
    // =========================================================================
    public function replyTicket(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120', // Maksimal file 5MB
        ]);

        $ticket = Ticket::findOrFail($id);

        // Upload lampiran jika ada
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'public');
        }

        // Simpan balasan ke database
        TicketFollowup::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::user()->employee_id, // Sesuai relasi user di table followup
            'message' => $request->message,
            'attachment' => $attachmentPath,
        ]);

        // LOGIC PINTAR: Jika status tiket sedang "Pending" (menunggu balasan user),
        // otomatis ubah statusnya kembali menjadi "In Progress" karena user sudah membalas.
        if ($ticket->status == 4) { // 4 = Pending
            $ticket->update(['status' => 3]); // 3 = In Progress
        }

        return back()->with('welcome_msg', 'Pesan balasan Anda berhasil terkirim!');
    }

    // =========================================================================
    // FUNGSI APPROVE TIKET (USER MENYETUJUI TIKET DITUTUP)
    // =========================================================================
    public function approveTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Ubah status menjadi Closed (6)
        $ticket->update([
            'status' => 6 // 6 = Closed (Ditutup permanen)
        ]);

        return back()->with('welcome_msg', 'Terima kasih! Tiket telah berhasil ditutup.');
    }
}