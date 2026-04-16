<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;

class ReportController extends Controller
{
    public function index()
    {
        // Mengambil data armada untuk ditampilkan di tabel Manajemen Laporan
        $vessels = Vessel::all();
        return view('reports.index', compact('vessels'));
    }

    public function store(Request $request)
    {
        $action = $request->input('action_type');
        $pesan = $action === 'draft' ? 'Laporan berhasil disimpan sebagai DRAFT.' : 'Laporan FINAL berhasil disubmit.';

        // Setelah submit, kembalikan ke halaman manajemen laporan
        return redirect()->route('reports.index')->with('success', $pesan);
    }

    public function history()
    {
        return view('reports.history');
    }
}
