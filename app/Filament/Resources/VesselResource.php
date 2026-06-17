<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VesselResource\Pages;
use App\Models\Vessel;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;

class VesselResource extends Resource
{
    protected static ?string $model = Vessel::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Vessel Management';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Kapal')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Nama Perusahaan (Company)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('vessel_name')
                            ->label('Nama Kapal')
                            ->required()
                            ->maxLength(255),

                        \Filament\Forms\Components\Select::make('pic_name')
                            ->label('Nama PIC Kapal')
                            ->options(
                                \App\Models\Employee::where('is_active', 1)->pluck('full_name', 'full_name')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan / Spesifikasi Kapal')
                            ->columnSpanFull()
                            ->rows(3),

                        KeyValue::make('cctv_names')
                            ->label('Konfigurasi Channel CCTV (Universal)')
                            ->keyLabel('Kode CH Asli (AJG, BRT, dll)')
                            ->valueLabel('Nama Label (Tampil di PDF/Monitoring)')
                            ->addActionLabel('Tambah Kamera Baru')
                            ->reorderable()
                            ->default([
                                'AJG' => 'CCTV 1 (Cam A)',
                                'BRT' => 'CCTV 2 (Cam B)',
                                'CCR' => 'CCTV 3 (Cam C)',
                                'ECR' => 'CCTV 4 (Cam D)',
                                'WKN' => 'CCTV 5 (Cam E)',
                                'WKR' => 'CCTV 6 (Cam F)',
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('hint_owner')
                    ->label('Cara Buat Akun Owner')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading('Panduan Akun Multi-Tenant (Vessel Owner)')
                    ->modalDescription(new HtmlString('Fitur ini digunakan untuk membuat akses khusus bagi <strong>Klien / Owner Perusahaan (PT)</strong> agar mereka bisa memantau CCTV kapal mereka sendiri secara mandiri. <br><br><strong>Mekanisme Sistem:</strong><br>1. Klik tombol hijau <strong>"Buat Akun Owner (PT)"</strong>.<br>2. Pilih Nama PT (Sistem otomatis mendeteksi PT yang terdaftar di database).<br>3. Isi Nama, Email, dan Password Akun.<br>4. Saat User tersebut Login, sistem secara gaib akan mengunci hak aksesnya menjadi <strong>Read-Only</strong> dan memblokir kamera milik perusahaan lain agar tidak saling intip.'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Paham!'),

                Action::make('create_owner')
                    ->label('Buat Akun Owner (PT)')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('company')
                            ->label('Pilih Perusahaan (PT)')
                            ->options(fn () => Vessel::select('company_name')->distinct()->pluck('company_name', 'company_name')->toArray())
                            ->required(),
                        Forms\Components\TextInput::make('name')->label('Nama Lengkap (PIC Owner)')->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email Akses (Work Email)')
                            ->email()
                            ->unique(table: \App\Models\User::class, column: 'email_work')
                            ->required(),

                        Forms\Components\TextInput::make('password')->password()->required(),
                    ])
                    ->action(function (array $data) {
                        $autoCode = 'OWN-' . time();

                        \App\Models\User::create([
                            'employee_code' => $autoCode,
                            'full_name' => $data['name'],
                            'email_work' => $data['email'],
                            'password' => $data['password'],
                            'role' => 'owner',
                            'company' => $data['company'],

                            // 👇 INI YANG TERLEWAT OLEH ANDA MAS LEVI! Wajib ada company_id untuk database HRD
                            'company_id' => 1,

                            'is_active' => 1,
                            'access_app_IT_Management_System' => 1,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Akun Owner Berhasil Dibuat!')
                            ->body('User ID (Sistem): ' . $autoCode)
                            ->success()
                            ->send();
                    })
            ])
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Nama Kapal')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pic_name')
                    ->label('PIC')
                    ->searchable(),

                Tables\Columns\TextColumn::make('cctv_names')
                    ->label('Total Kamera')
                    ->getStateUsing(fn (Vessel $record): string => is_array($record->cctv_names) ? count($record->cctv_names) . ' Kamera' : '6 Kamera (Default)')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Didaftarkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth('4xl'),
                Tables\Actions\EditAction::make()->modalWidth('4xl'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVessels::route('/'),
        ];
    }
}
