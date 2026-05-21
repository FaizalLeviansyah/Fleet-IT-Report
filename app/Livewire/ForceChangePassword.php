<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForceChangePassword extends Component
{
    public $password;
    public $password_confirmation;

    public function submit()
    {
        // 1. Validasi Input
        $this->validate([
            'password' => 'required|min:6|same:password_confirmation',
        ], [
            'password.required' => 'Password baru wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.same' => 'Konfirmasi password tidak cocok!'
        ]);

        $user = auth()->user();
        
        // 2. Simpan sandi baru (otomatis terenkripsi di Model User)
        $user->update([
            'password' => $this->password
        ]);

        // 3. Picu Event JS tanpa memanipulasi session di backend agar tidak crash (Error 419)
        $this->dispatch('password-updated');
    }

    public function render()
    {
        $isDefault = auth()->check() && Hash::check('amarin123', auth()->user()->password);
        
        if (!$isDefault) {
            return <<<'HTML'
            <div></div>
            HTML;
        }

        return <<<'HTML'
        <div style="position: fixed; inset: 0; z-index: 9999999; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); display: flex; align-items: center; justify-content: center; padding: 20px;">
            <div style="background: white; width: 100%; max-width: 420px; border-radius: 1.5rem; padding: 2.5rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); position: relative; overflow: hidden;">
                
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: linear-gradient(90deg, #EF4444, #DC2626);"></div>

                <div style="text-align: center; margin-bottom: 1.75rem;">
                    <div style="width: 55px; height: 55px; background: #FEF2F2; color: #EF4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: 0 0 15px rgba(239,68,68,0.2);">
                        <svg style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; font-family: 'Poppins', sans-serif;">Peringatan Keamanan</h2>
                    <p style="font-size: 0.8rem; color: #4B5563; line-height: 1.5;">Anda masih menggunakan sandi <b>(amarin123)</b>. Demi keamanan, Anda <b>wajib menggantinya</b> sekarang juga.</p>
                </div>

                <form wire:submit="submit" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #374151; margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.05em;">Sandi Baru</label>
                        <input type="password" wire:model="password" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.75rem; font-size: 0.875rem; outline: none; transition: all 0.2s; background: #FAFBFC;" required placeholder="••••••••">
                        @error('password') <span style="color: #EF4444; font-size: 0.7rem; font-weight: 700; margin-top: 0.4rem; display: block;">* {{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #374151; margin-bottom: 0.3rem; text-transform: uppercase; letter-spacing: 0.05em;">Ulangi Sandi Baru</label>
                        <input type="password" wire:model="password_confirmation" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #D1D5DB; border-radius: 0.75rem; font-size: 0.875rem; outline: none; transition: all 0.2s; background: #FAFBFC;" required placeholder="••••••••">
                    </div>
                    
                    <button type="submit" style="width: 100%; padding: 0.85rem; background: #031E49; color: white; font-weight: 700; font-size: 0.875rem; border-radius: 0.75rem; border: none; cursor: pointer; margin-top: 0.5rem; box-shadow: 0 4px 10px -2px rgba(3, 30, 73, 0.3); transition: all 0.3s;" onmouseover="this.style.background='#1E40AF'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#031E49'; this.style.transform='translateY(0)';">
                        <span wire:loading.remove>Simpan Sandi Baru</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </form>
            </div>

            <script>
                window.addEventListener('password-updated', event => {
                    Swal.fire({
                        title: 'Keamanan Diperbarui!',
                        text: 'Sandi berhasil diganti. Demi standar keamanan sistem, sesi Anda saat ini diakhiri. Silakan masuk kembali menggunakan sandi baru Anda.',
                        icon: 'success',
                        showConfirmButton: true,
                        confirmButtonText: 'Masuk Ulang',
                        confirmButtonColor: '#031E49',
                        allowOutsideClick: false, // Tidak bisa di-klik sembarangan
                        allowEscapeKey: false,    // Tidak bisa pencet ESC
                        background: 'rgba(255,255,255,0.98)',
                        backdrop: 'rgba(15,23,42,0.9)'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Munculkan animasi loading sebelum pindah halaman
                            const loader = document.getElementById("amarin-global-loader");
                            if(loader) { loader.style.visibility = "visible"; loader.style.opacity = "1"; }
                            
                            // Arahkan ke halaman login
                            window.location.href = '/admin/login';
                        }
                    });
                });
            </script>
        </div>
        HTML;
    }
}