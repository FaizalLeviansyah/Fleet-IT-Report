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
            ->modalWidth('2xl') // Ukuran Pop-Up lebar dan proporsional
            ->fillForm(fn (): array => auth()->user()->toArray())
            ->form([
                Section::make('Identitas Pekerjaan')
                    ->schema([
                        // Jika kolom avatar_url dan jabatan belum ada di database, ini tidak akan error
                        // karena kita pakaikan ->dehydrated(false) untuk sementera.
                        FileUpload::make('avatar_url')
                            ->label('Foto Profil')
                            ->avatar()
                            ->imageEditor()
                            ->circleCropper()
                            ->maxSize(2048)
                            ->dehydrated(false) // Ganti jadi true jika kolom DB sudah siap
                            ->alignCenter()
                            ->columnSpanFull(),

                        Textarea::make('jabatan')
                            ->label('Jabatan / Posisi')
                            ->placeholder('Contoh: IT Support Regional')
                            ->rows(2)
                            ->dehydrated(false) // Ganti jadi true jika kolom DB sudah siap
                            ->columnSpanFull(),
                    ]),
                Section::make('Informasi Pribadi')
                    ->schema([
                        TextInput::make('full_name')->label('Nama Lengkap')->required(),
                        TextInput::make('email_work')->label('Email Kerja')->email()->required(),
                    ])->columns(2),
                Section::make('Keamanan (Ubah Password)')
                    ->schema([
                        TextInput::make('password')->label('Password Baru')->password()->revealable()->dehydrateStateUsing(fn ($state) => Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(false),
                        TextInput::make('passwordConfirmation')->label('Konfirmasi Password Baru')->password()->revealable()->requiredWith('password')->same('password')->dehydrated(false),
                    ])->columns(2),
            ])
            ->action(function (array $data): void {
                auth()->user()->update($data);
                Notification::make()
                    ->title('Profil Berhasil Diperbarui!')
                    ->success()
                    ->send();
            });
    }

    public function render()
    {
        return view('livewire.edit-profile-modal');
    }
}
