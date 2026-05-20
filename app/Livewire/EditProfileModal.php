<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class EditProfileModal extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function editProfileAction(): Action
    {
        return Action::make('editProfile')
            ->modalHeading('Profil Akun & Keamanan')
            ->modalDescription('Perbarui data identitas dan kredensial login Anda.')
            ->modalSubmitActionLabel('Simpan Perubahan')
            ->modalWidth('2xl')
            ->fillForm(fn (): array => auth()->user()->toArray())
            ->form([
                Section::make('Identitas Pekerjaan')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar()
                            ->directory('avatars') // Simpan di storage/app/public/avatars
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->alignCenter()
                            ->columnSpanFull(),

                        Textarea::make('jabatan')
                            ->label('Jabatan / Posisi')
                            ->placeholder('Contoh: IT Support Regional')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
                Section::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('full_name')->label('Nama Lengkap')->required(),

                        // 👇 KUNCI FIX PARTIAL UPDATE: Kita suruh Laravel mengabaikan email milik user ini sendiri saat divalidasi!
                        TextInput::make('email_work')
                            ->label('Email Kerja')
                            ->email()
                            ->required()
                            // 👇 UBAH BARIS INI 👇
                            ->unique(
                                table: \App\Models\User::class,
                                column: 'email_work',
                                ignorable: auth()->user()
                            ),
                    ])->columns(2),
                Section::make('Keamanan (Ubah Password)')
                    ->schema([
                        TextInput::make('password')->label('Password Baru')->password()->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(false), // Tidak wajib diisi
                        TextInput::make('passwordConfirmation')->label('Konfirmasi Password Baru')->password()->revealable()
                            ->requiredWith('password')->same('password')->dehydrated(false),
                    ])->columns(2),
            ])
            ->action(function (array $data): void {
                // 1. Simpan ke Database
                auth()->user()->update($data);

                // 2. Munculkan Notif Hijau
                Notification::make()->title('Profil Berhasil Diperbarui!')->success()->send();

                // 👇 3. KUNCI FIX NAVBAR: Paksa browser me-reload halaman SAAT ITU JUGA agar Navbar Profile merender data terbaru! 👇
                redirect(request()->header('Referer'));
            });
    }

    public function render()
    {
        return view('livewire.edit-profile-modal');
    }
}
