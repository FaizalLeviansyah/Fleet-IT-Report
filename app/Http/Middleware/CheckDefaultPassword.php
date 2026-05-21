<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class CheckDefaultPassword
{
    public function handle(Request $request, Closure $next)
    {
        // Jika user login, dan passwordnya adalah "amarin123"
        if (auth()->check() && Hash::check('amarin123', auth()->user()->password)) {
            // Cegah agar tidak looping (izinkan akses ke Livewire/Logout)
            if (!$request->routeIs('filament.admin.auth.logout') && !str_contains($request->path(), 'livewire')) {

                // Beri peringatan dan buka modal ganti password paksa
                Notification::make()
                    ->title('KEAMANAN SISTEM!')
                    ->body('Anda masih menggunakan password default. Wajib ganti sekarang!')
                    ->danger()
                    ->send();

                // Paksa buka pop-up Edit Profile secara paksa lewat JS
                return response()->setContent(
                    $next($request)->getContent() . "<script>window.dispatchEvent(new Event('open-profile-modal'));</script>"
                );
            }
        }
        return $next($request);
    }
}
