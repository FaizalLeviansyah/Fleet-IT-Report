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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    // --- PENGATURAN SIDEBAR (Cukup 1x saja) ---
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Manage PIC / Crew';
    protected static ?int $navigationSort = 2;

    // --- FITUR KEAMANAN: HANYA ADMIN YANG BISA LIHAT MENU INI ---
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
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

                        // KOLOM PASSWORD
                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create'),

                        // TOGGLE AKSES
                        Forms\Components\Toggle::make('access_app_IT_Management_System')
                            ->label('Izinkan Akses IT Management System?')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')->label('Kode Pegawai (NIK)'),
                        Forms\Components\Select::make('role')
                            ->options(['admin' => 'Admin', 'user' => 'User Biasa']),
                        Forms\Components\Select::make('employment_status')
                            ->options(['Active' => 'Active', 'Inactive' => 'Inactive'])
                            ->default('Active'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Akun (Aktif/Non-Aktif)')
                            ->default(true),
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
            // 'create' => Pages\CreateEmployee::route('/create'),
            // 'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
