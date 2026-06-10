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
        // 1. Eksekusi login bawaan Filament (Cek email & password)
        $response = parent::authenticate();

        // 2. Ambil data user yang baru saja sukses login
        $user = \Illuminate\Support\Facades\Auth::user();

        // 3. 🚨 LOGIKA PEMISAH JALUR (GATEWAY) 🚨
        if ($user && $user->role === 'User Biasa') {
            // Gunakan redirect bawaan Livewire agar mulus pindah ke portal HRIS
            $this->redirect(route('portal.dashboard'), navigate: false);
            return null; 
        }

        // 4. Jika yang login Admin, biarkan masuk ke dasbor Filament
        return $response;
    }
}