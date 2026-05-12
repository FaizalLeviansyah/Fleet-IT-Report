<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    // Menerjemahkan input 'email' dari form menjadi 'email_work' ke database
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email_work' => $data['email'],
            'password'  => $data['password'],
        ];
    }
}
