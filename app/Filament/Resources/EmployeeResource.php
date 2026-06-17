<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use App\Models\Vessel;
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

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Manage PIC / Crew';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return strtolower(auth()->user()->role) === 'admin';
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
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->formatStateUsing(fn () => null)
                            ->required(fn (string $context): bool => $context === 'create'),

                        \Filament\Forms\Components\Toggle::make('access_app_IT_Management_System')
                            ->label('Beri Akses ITSM Stack (Wajib agar bisa Login)')
                            ->live()
                            ->default(true),

                        // 💡 SMART FORM: Role Dinamis
                        Forms\Components\Select::make('role')
                            ->label('Role Akses Sistem')
                            ->options([
                                'admin' => '👑 Admin (Tim IT)',
                                'staff' => '💼 User Biasa (Pegawai/Crew)',
                                'owner' => '🏢 Client (Vessel Owner)', // 👈 Tambahan Client
                            ])
                            ->live() // Wajib live agar form di bawahnya bisa bereaksi
                            ->native(false)
                            ->required(fn (Get $get) => $get('access_app_IT_Management_System') === true)
                            ->visible(fn (Get $get) => $get('access_app_IT_Management_System') === true),
                    ])->columns(2),


                \Filament\Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->label('Kode Pegawai / User ID')
                            ->default(fn () => 'USR-' . time()) // Auto-generate
                            ->required(),

                        // Default Company ID (Amarin) agar tidak Error 1364
                        Forms\Components\Hidden::make('company_id')->default(1),

                        // 💡 SMART FORM: Hanya muncul jika rolenya adalah 'owner'
                        Forms\Components\Select::make('company')
                            ->label('Perusahaan Klien (Nama PT)')
                            ->options(fn () => Vessel::select('company_name')->distinct()->pluck('company_name', 'company_name')->toArray())
                            ->visible(fn (Get $get) => $get('role') === 'owner')
                            ->required(fn (Get $get) => $get('role') === 'owner')
                            ->helperText('Pilih PT klien. User ini hanya bisa melihat CCTV kapal milik PT ini.'),

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
                            ->visible(fn (Get $get) => in_array($get('role'), ['admin', 'staff']))
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // 💡 SMART UX: Filter Khusus untuk memisahkan Kasta Aktor!
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filter Kategori User')
                    ->options([
                        'admin' => '👑 Admin / Tim IT',
                        'staff' => '💼 Pegawai / Crew',
                        'owner' => '🏢 Client / Vessel Owner',
                    ])
                    ->native(false),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email_work')
                    ->label('Email / Username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Tipe Akun')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match (strtolower($state)) {
                        'admin' => '👑 Admin',
                        'staff' => '💼 Crew/Staff',
                        'owner' => '🏢 Klien (Owner)',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'admin' => 'danger',
                        'owner' => 'success',
                        default => 'info',
                    }),

                Tables\Columns\TextColumn::make('company')
                    ->label('Nama PT (Khusus Klien)')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\ToggleColumn::make('access_app_IT_Management_System')
                    ->label('Akses ITSM')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
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
