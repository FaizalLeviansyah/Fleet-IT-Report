<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel; // Memanggil model Vessel

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil semua data kapal dari database
        $vessels = Vessel::all();

        // Mengirim data ke tampilan dashboard
        return view('dashboard', compact('vessels'));
    }
}
