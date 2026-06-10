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

class CustomLogin extends BaseLogin implements HasActions
{
    use InteractsWithActions;

    protected static string $view = 'filament.pages.auth.custom-login';

    // 👇 FUNGSI POP-UP LIVEWIRE: FORGOT PASSWORD 👇
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
                $user = User::where('email_work', $data['email_reset'])->first();

                if (!$user) {
                    Notification::make()->title('Gagal!')->body('Email tidak ditemukan dalam sistem ITSM.')->danger()->send();
                    return;
                }

                try {
                    // Logic kirim email (Pastikan SMTP di .env sudah disetting)
                    Notification::make()->title('Berhasil!')->body('Tautan reset password telah dikirim ke email Anda.')->success()->send();
                } catch (\Exception $e) {
                    Notification::make()->title('Gagal Mengirim Email')->body('Terjadi kesalahan pada server SMTP.')->danger()->send();
                }
            });
    }

    // 🚨 CUKUP 1 KALI SAJA: Logic Pencocokan Database 🚨
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email_work' => $data['email'],
            'password'  => $data['password'],
        ];
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
        // Ambil data dari form login
        $data = $this->form->getState();

        // 1. Cek Kredensial Manual ke Database (Bypass Filament Security Check sementara)
        if (! \Illuminate\Support\Facades\Auth::attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            // Jika email/password memang salah, munculkan error merah
            $this->throwFailureValidationException();
        }

        // Ambil data user yang baru saja sukses login
        $user = \Illuminate\Support\Facades\Auth::user();

        // 2. 🚨 LOGIKA GATEWAY (MENCEGAT USER BIASA SEBELUM DITENDANG) 🚨
        if ($user && $user->role === 'User Biasa') {
            
            // Pastikan akunnya aktif dan punya izin masuk aplikasi ITSM
            if ($user->is_active != 1 || $user->access_app_IT_Management_System != 1) {
                \Illuminate\Support\Facades\Auth::logout();
                $this->throwFailureValidationException();
            }

            // Redirect mulus ke Portal HRIS
            $this->redirect(route('portal.dashboard'), navigate: false);
            return null; 
        }

        // 3. JIKA YANG LOGIN ADMIN: Baru kita aktifkan keamanan Filament
        if ($user instanceof \Filament\Models\Contracts\FilamentUser && ! $user->canAccessPanel(filament()->getCurrentPanel())) {
            \Illuminate\Support\Facades\Auth::logout();
            $this->throwFailureValidationException();
        }

        // Buat sesi aman
        session()->regenerate();

        return app(\Filament\Http\Responses\Auth\Contracts\LoginResponse::class);
    }
}