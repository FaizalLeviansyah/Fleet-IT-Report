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
        // Cek apakah user masih menggunakan password default 'amarin123'
        $isDefaultPassword = auth()->check() && Hash::check('amarin123', auth()->user()->password);

        return Action::make('editProfile')
            ->modalHeading('Profil Akun & Keamanan')
            ->modalDescription('Perbarui data identitas dan kredensial login Anda.')
            ->modalSubmitActionLabel('Simpan Perubahan')
            ->modalWidth('2xl')
            // 👇 FIX: MENGHILANGKAN TOMBOL 'X' DI POJOK KANAN ATAS JIKA PASSWORD DEFAULT 👇
            ->modalCloseButton(! $isDefaultPassword)
            ->closeModalByClickingAway(! $isDefaultPassword)
            ->closeModalByEscaping(! $isDefaultPassword)
            ->fillForm(fn (): array => auth()->user()->toArray())
            ->form([
                Section::make('Identitas Pekerjaan')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar()
                            ->directory('avatars')
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
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required(),

                        TextInput::make('email_work')
                            ->label('Email Kerja')
                            ->email()
                            ->required()
                            ->unique(
                                table: \App\Models\User::class,
                                column: 'email_work',
                                ignorable: auth()->user()
                            ),
                    ])->columns(2),
                Section::make('Keamanan (Ubah Password)')
                    ->description($isDefaultPassword ? '⚠️ Wajib diisi! Anda harus mengganti password default (amarin123) untuk alasan keamanan.' : '')
                    ->schema([
                        // 👇 FIX DOUBLE HASHING: Tidak ada lagi ->dehydrateStateUsing(fn ($state) => Hash::make($state)) 👇
                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required($isDefaultPassword), // Wajib diisi JIKA password saat ini adalah 'amarin123'

                        TextInput::make('passwordConfirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->requiredWith('password')
                            ->same('password')
                            ->dehydrated(false),
                    ])->columns(2),
            ])
            ->action(function (array $data): void {
                $user = auth()->user();
                $user->update($data);

                // 👇 FIX ANTI-LOGOUT SAAT GANTI PASSWORD DARI DALAM PROFIL 👇
                if (isset($data['password']) && filled($data['password'])) {
                    session()->put([
                        'password_hash_' . \Filament\Facades\Filament::auth()->getName() => $user->getAuthPassword()
                    ]);
                    \Filament\Facades\Filament::auth()->login($user, true);
                    session()->regenerate();
                }

                \Filament\Notifications\Notification::make()->title('Profil Berhasil Diperbarui!')->success()->send();
                redirect(request()->header('Referer'));
            });
    }

    public function render()
    {
        return view('livewire.edit-profile-modal');
    }
}
