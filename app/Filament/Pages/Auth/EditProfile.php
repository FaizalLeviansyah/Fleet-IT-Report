<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Profil Akun & Keamanan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION FOTO & JABATAN
                \Filament\Forms\Components\Section::make('Identitas Pekerjaan')
                    ->description('Lengkapi profil Anda agar mudah dikenali.')
                    ->schema([
                        // Catatan: Jika DB tidak punya kolom avatar_url, file ini tidak akan disimpan ke DB (hanya visual).
                        // Jika ingin tersimpan, pastikan Anda menambah kolom 'avatar_url' dan 'jabatan' di tabel tbl_employee.
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->dehydrated(false) // Jangan simpan ke DB dulu jika kolom belum ada
                            ->columnSpanFull()
                            ->alignCenter(),

                        Textarea::make('jabatan')
                            ->label('Jabatan / Posisi (Role)')
                            ->placeholder('Contoh: IT Support Regional Jakarta')
                            ->rows(2)
                            ->dehydrated(false) // Jangan simpan ke DB dulu jika kolom belum ada
                            ->columnSpanFull(),
                    ]),

                // SECTION INFO PRIBADI
                \Filament\Forms\Components\Section::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('full_name')->label('Nama Lengkap')->required()->maxLength(255),
                        TextInput::make('email_work')->label('Email Kerja (Username)')->email()->required()->unique(ignoreRecord: true),
                    ])->columns(2),

                // SECTION PASSWORD
                \Filament\Forms\Components\Section::make('Keamanan (Ubah Password)')
                    ->schema([
                        TextInput::make('password')->label('Password Baru')->password()->revealable()->dehydrateStateUsing(fn ($state) => Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(false),
                        TextInput::make('passwordConfirmation')->label('Konfirmasi Password Baru')->password()->revealable()->requiredWith('password')->same('password')->dehydrated(false),
                    ])->columns(2),
            ]);
    }
}
