<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{
    // Mengubah judul halaman
    protected static ?string $title = 'Profil Akun & Keamanan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Pribadi')
                    ->description('Data ini terhubung langsung dengan Master Karyawan HRD.')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                            
                        TextInput::make('email_work')
                            ->label('Email Kerja (Username)')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Ubah Password')
                    ->description('Kosongkan jika tidak ingin mengubah password.')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable() // Tombol lihat password (mata)
                            // Enkripsi otomatis sebelum masuk ke database
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            // Hanya simpan ke DB kalau form ini diisi
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(false), // Tidak wajib diisi
                            
                        TextInput::make('passwordConfirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->requiredWith('password') // Wajib diisi JIKA password baru diisi
                            ->same('password') // Harus sama dengan form di atas
                            ->dehydrated(false), // Jangan masukkan field ini ke database
                    ])->columns(2),
            ]);
    }
}