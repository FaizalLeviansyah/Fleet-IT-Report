<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomLogin extends BaseLogin implements HasActions
{
    use InteractsWithActions;

    protected static string $view = 'filament.pages.auth.custom-login';

    public string $loginDestination = 'auto';

    public function forgotPasswordAction(): Action
    {
        return Action::make('forgotPassword')
            ->label('Forgot password?')
            ->link()
            ->color('primary')
            ->modalHeading('Reset Password Sistem')
            ->modalDescription('Masukkan email terdaftar Anda. Kami akan mengirimkan instruksi pemulihan ke email tersebut.')
            ->modalSubmitActionLabel('Kirim Link Reset')
            ->modalWidth('md')
            ->form([
                TextInput::make('email_reset')
                    ->label('EMAIL KERJA (TERDAFTAR)')
                    ->email()
                    ->required()
                    ->placeholder('contoh@amarin.biz.id')
                    ->prefixIcon('heroicon-m-envelope'),
            ])
            ->action(function (array $data): void {
                // 💡 FIX: Cari di kedua kolom (Pegawai & Owner)
                $user = User::where('email_work', $data['email_reset'])->orWhere('email', $data['email_reset'])->first();

                if (!$user) {
                    Notification::make()->title('Gagal!')->body('Email tidak ditemukan dalam sistem ITSM.')->danger()->send();
                    return;
                }
                Notification::make()->title('Berhasil!')->body('Tautan reset password telah dikirim ke email Anda.')->success()->send();
            });
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('EMPLOYEE ID / EMAIL')
                    ->placeholder('Enter your credentials')
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->prefixIcon('heroicon-m-user')
                    ->extraInputAttributes(['class' => 'font-medium']),

                TextInput::make('password')
                    ->label('PASSWORD')
                    ->placeholder('••••••••')
                    ->password()
                    ->revealable(true)
                    ->required()
                    ->prefixIcon('heroicon-m-lock-closed')
                    ->extraInputAttributes(['class' => 'font-medium']),

                Checkbox::make('remember')
                    ->label('Remember me')
                    ->inline(false),
            ]);
    }

    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $data = $this->form->getState();

        // 1. 💡 FIX: CARI USER DI KOLOM EMAIL_WORK (Pegawai) ATAU EMAIL (Owner)
        $user = User::where('email_work', $data['email'])->orWhere('email', $data['email'])->first();

        // 2. JIKA USER TIDAK ADA ATAU PASSWORD SALAH -> LEMPAR ERROR
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            $this->throwFailureValidationException();
        }

        // 3. JIKA PASSWORD BENAR, LAKUKAN PENGECEKAN KASTA
        // Yang boleh masuk Filament Dasbor adalah Admin, Tim IT, DAN Owner!
        $isFilamentUser = in_array($user->role, ['admin', 'owner']) || $user->is_it_team == 1;
        $destination = $this->loginDestination;

        // Cek Admin / Owner Nyasar Ke Portal Karyawan
        if ($destination === 'portal' && $isFilamentUser) {
            Notification::make()->title('Salah Jalur! 🛑')->body('Akun Anda dikonfigurasi untuk Dasbor Utama. Silakan ganti pilihan login Anda.')->danger()->send();
            return null;
        }

        // Cek Pegawai Biasa Nyasar Ke Dasbor Admin / Owner
        if (in_array($destination, ['admin', 'owner']) && !$isFilamentUser) {
            Notification::make()->title('Akses Ilegal! 🚫')->body('Anda tidak memiliki akses ke Dasbor Utama. Silakan pilih "Employee Portal".')->danger()->send();
            return null;
        }

        // 4. JIKA SEMUA LOLOS, LOGIN SECARA PAKSA MENGGUNAKAN GUARD FILAMENT
        filament()->auth()->login($user, $data['remember'] ?? false);
        session()->regenerate();

        // Ambil nama depan untuk sapaan (Karena owner pakai 'name', pegawai pakai 'full_name')
        $firstName = explode(' ', $user->full_name ?? $user->name ?? 'User')[0];

        // 5. NOTIFIKASI & REDIRECT SESUAI KASTA
        if ($isFilamentUser) {
            Notification::make()
                ->title('Login Berhasil 🎉')
                ->body('Selamat datang kembali, ' . $firstName . '!')
                ->success()
                ->send();

            return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
        } else {
            session()->flash('welcome_msg', 'Selamat datang di Portal Layanan IT, ' . $firstName . '! 👋');
            $this->redirect(route('portal.dashboard'), navigate: false);
            return null;
        }
    }
}
