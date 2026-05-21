<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;

class CustomLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.custom-login';

    // 👇 INI KUNCI JAWABANNYA! Menyambungkan input 'email' ke kolom 'email_work' 👇
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
}
