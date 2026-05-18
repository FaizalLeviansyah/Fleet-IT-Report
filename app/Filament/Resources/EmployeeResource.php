<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    // --- PENGATURAN SIDEBAR ---
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Manage PIC / Crew';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Data Pegawai / Crew')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->label('Kode Pegawai (NIK)')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email_work')
                            ->label('Email Kerja')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->label('Role Akses')
                            ->options([
                                'admin' => 'Admin',
                                'user' => 'User Biasa',
                                'manager' => 'Manager',
                            ]),

                        Forms\Components\Select::make('employment_status')
                            ->label('Status Karyawan')
                            ->options([
                                'Active' => 'Active',
                                'Inactive' => 'Inactive',
                                'Resigned' => 'Resigned',
                            ])->default('Active'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')
                    ->label('Kode/NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email_work')
                    ->label('Email Kerja')
                    ->searchable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('employment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        default => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
