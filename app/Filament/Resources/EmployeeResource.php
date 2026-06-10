<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Get;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    // --- PENGATURAN SIDEBAR ---
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Manage PIC / Crew';
    protected static ?int $navigationSort = 2;

    // --- FITUR KEAMANAN: HANYA ADMIN YANG BISA LIHAT MENU INI ---
    public static function canViewAny(): bool
    {
        // Pengecekan disesuaikan dengan ejaan huruf besar/kecil di DB
        return auth()->user()->role === 'admin' || auth()->user()->role === 'Admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Kredensial & Akses Aplikasi')
                    ->description('Kelola login dan izin akses pegawai ke sistem IT.')
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required(),

                        Forms\Components\TextInput::make('email_work')
                            ->label('Email Kerja (Username)')
                            ->email()
                            ->required(),

                        // 🚨 FIX 1: Kolom Password sekarang DIENKRIPSI OTOMATIS (Hash) agar user bisa login
                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            // 👇 FIX: Jangan tarik password hash dari database ke form
                            ->dehydrated(fn ($state) => filled($state)) 
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            // 👇 FIX: Kosongkan field saat form Edit dibuka
                            ->formatStateUsing(fn () => null) 
                            ->required(fn (string $context): bool => $context === 'create'),

                        // TOGGLE AKSES
                        \Filament\Forms\Components\Toggle::make('access_app_IT_Management_System')
                            ->label('Beri Akses ITSM Stack')
                            ->live(), 

                        // 🚨 FIX 2: Hanya ada SATU kolom Role (Sudah disesuaikan dengan DB Anda)
                        Forms\Components\Select::make('role')
                            ->label('Role Akses Sistem')
                            ->options([
                                'Admin' => '👑 Admin (Tim IT)',
                                'User Biasa' => '💼 User Biasa (Pegawai/Crew)',
                            ])
                            ->native(false)
                            ->required(fn (Get $get) => $get('access_app_IT_Management_System') === true)
                            ->visible(fn (Get $get) => $get('access_app_IT_Management_System') === true), 
                    ])->columns(2),
                    

                \Filament\Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->label('Kode Pegawai (NIK)'),
                        
                        // 🚨 FIX 3: Kolom company_id dimasukkan ke dalam section agar tampilannya rapi
                        Forms\Components\Select::make('company_id')
                            ->label('Perusahaan')
                            ->options([
                                1 => 'PT Amarin Ship Management',
                                2 => 'PT Amarin Crewing Services',
                                3 => 'PT Caraka Tirta Pratama',
                            ])
                            ->required(),

                        Forms\Components\Select::make('employment_status')
                            ->label('Status Kerja')
                            ->options(['Active' => 'Active', 'Inactive' => 'Inactive'])
                            ->default('Active'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Akun (Aktif/Non-Aktif)')
                            ->default(true),

                        Forms\Components\Toggle::make('is_it_team')
                            ->label('Jadikan sebagai Teknisi IT')
                            ->onColor('success')
                            ->offColor('gray')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email_work')
                    ->label('Email / Username')
                    ->searchable(),

                // SAKLAR AJAIB
                Tables\Columns\ToggleColumn::make('access_app_IT_Management_System')
                    ->label('Akses App IT')
                    ->onColor('success')
                    ->offColor('danger')
                    ->afterStateUpdated(function ($state) {
                        \Filament\Notifications\Notification::make()
                            ->title($state ? 'Akses Diberikan' : 'Akses Dicabut')
                            ->body('Izin akses aplikasi IT pegawai telah diperbarui.')
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('employment_status')
                    ->label('Status Kerja')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        default => 'danger',
                    }),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth('4xl'),
                Tables\Actions\EditAction::make()->modalWidth('4xl'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
        ];
    }
}