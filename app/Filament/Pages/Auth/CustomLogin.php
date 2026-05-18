<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    // Mengubah pencarian 'email' bawaan Filament menjadi 'email_work' sesuai database HRD Anda
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email_work' => $data['email'], // Form input tetap email, tapi dicari di kolom email_work
            'password'  => $data['password'],
        ];
    }
}
