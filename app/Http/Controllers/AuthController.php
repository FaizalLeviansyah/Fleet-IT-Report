<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan Halaman Login Kaca (Glassmorphism)
    public function showLoginForm()
    {
        // Jika sudah login, langsung usir ke jalannya masing-masing
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    // Proses Pengecekan Kredensial
    public function login(Request $request)
    {
        // Validasi input dari form HTML Anda (memakai email_work)
        $request->validate([
            'email_work' => 'required|email',
            'password' => 'required'
        ]);

        // Mapping ke kolom 'email' di tabel users
        $credentials = [
            'email' => $request->email_work,
            'password' => $request->password,
        ];

        // Jika Login BERHASIL
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Panggil fungsi Pengatur Lalu Lintas
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Jika GAGAL
        return back()->withErrors([
            'email_work' => 'Email atau Password salah, silakan coba lagi.',
        ]);
    }

    // Fungsi Logout untuk Portal
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // 🚦 FUNGSI PENGATUR LALU LINTAS (ROUTER) 🚦
    private function redirectBasedOnRole($user)
    {
        // ASUMSI: Anda punya kolom 'role' di tabel users (Atau bisa pakai is_it_team, dll)

        // 1. Jika yang login ADMIN / TIM IT -> Lempar ke pintu baja (Filament)
        if ($user->role === 'admin') {
            return redirect()->to('/admin');
        }

        // 2. Jika yang login KAPAL -> Lempar ke Dashboard Kapal
        elseif ($user->role === 'vessel') {
            return redirect()->route('portal.dashboard'); // Nanti bisa diganti ke portal.vessel
        }

        // 3. Jika yang login KARYAWAN DARAT -> Lempar ke Dashboard Pegawai
        else {
            return redirect()->route('portal.dashboard');
        }
    }
}
