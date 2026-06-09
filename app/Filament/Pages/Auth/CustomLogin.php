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
            ->link() // Ubah wujud jadi teks link (bukan tombol kotak)
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
                    // \Illuminate\Support\Facades\Password::broker()->sendResetLink(['email_work' => $data['email_reset']]);

                    Notification::make()->title('Berhasil!')->body('Tautan reset password telah dikirim ke email Anda.')->success()->send();
                } catch (\Exception $e) {
                    Notification::make()->title('Gagal Mengirim Email')->body('Terjadi kesalahan pada server SMTP.')->danger()->send();
                }
            });
    }

    // Logic Login Asli
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email_work' => $data['email'],
            'password'  => $data['password'],
        ];
    }
    // app/Filament/Pages/Auth/CustomLogin.php

protected function getRedirectPath(): string
{
    $user = \Illuminate\Support\Facades\Auth::user();

    // Jika admin, tetap di panel Filament
    if ($user->role === 'admin') {
        return '/admin';
    }

    // Jika employee atau vessel, lempar keluar ke route portal Blade
    return '/portal/dashboard';
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
        // 1. Jalankan proses login bawaan Filament
        $response = parent::authenticate();

        // 2. Cek siapa yang baru saja login
        $user = \Illuminate\Support\Facades\Auth::user();

        // 3. Jika dia Employee atau Vessel, LEMPAR PAKSA ke Portal HRIS-Style
        if ($user && in_array($user->role, ['employee', 'vessel'])) {
            redirect()->intended(route('portal.dashboard'))->send();
            exit;
        }

        // 4. Jika Admin, biarkan masuk ke Filament
        return $response;
    }
}
