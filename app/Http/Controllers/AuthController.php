<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email_work' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login menggunakan Auth Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika berhasil, arahkan ke dashboard
            return redirect()->intended('/');
        }

        // Jika gagal, kembalikan ke form login dengan pesan error
        return back()->withErrors([
            'email_work' => 'Kredensial email atau password tidak cocok.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
