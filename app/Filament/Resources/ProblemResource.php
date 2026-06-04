<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProblemResource\Pages;
use App\Models\Problem;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProblemResource extends Resource
{
    protected static ?string $model = Problem::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationLabel = 'ITSM / Problem';
    protected static ?string $navigationGroup = 'IT MANAGEMENT';
    protected static ?int $navigationSort = 2;

    // KUNCI KEAMANAN: HANYA TIM IT YANG BISA LIHAT MENU INI
    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->full_name, [
            'FAIZAL LEVIANSYAH', 'FARHAN ARIF INDIARTO', 'HENDRI SETIO PRAKOSO'
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identifikasi Masalah (Problem)')
                    ->icon('heroicon-o-exclamation-circle')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Judul Problem / Akar Masalah')
                            ->placeholder('Contoh: Kabel Fiber Optik Pusat Putus')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi / Root Cause Analysis')
                            ->placeholder('Jelaskan detail penyebab utama masalah ini terjadi...')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('assigned_to_id')
                            ->label('Ditangani Oleh (Teknisi)')
                            ->options(User::whereIn('full_name', ['FAIZAL LEVIANSYAH', 'FARHAN ARIF INDIARTO', 'HENDRI SETIO PRAKOSO'])->pluck('full_name', 'employee_id'))
                            ->searchable()
                            ->required()
                            ->prefixIcon('heroicon-m-wrench-screwdriver'),

                        Forms\Components\Select::make('status')
                            ->label('Status Problem')
                            ->options([
                                1 => '🆕 New (Investigasi Baru)',
                                2 => '👨‍💻 Processing (Sedang Diperbaiki)',
                                3 => '✅ Solved (Masalah Selesai)',
                            ])
                            ->default(1)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Judul Problem')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('assignedTo.full_name')
                    ->label('Teknisi')
                    ->icon('heroicon-m-wrench-screwdriver'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'New', 2 => 'Processing', 3 => 'Solved', default => 'Unknown'
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger', 2 => 'warning', 3 => 'success', default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('tickets_count')
                    ->label('Total Tiket Terhubung')
                    ->counts('tickets') // Menghitung jumlah tiket yang di-attach
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Kejadian')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            // 👇 Pakai alamat lengkap agar Laravel tidak nyasar 👇
            \App\Filament\Resources\ProblemResource\RelationManagers\TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProblems::route('/'),
            'create' => Pages\CreateProblem::route('/create'),
            'edit' => Pages\EditProblem::route('/{record}/edit'),
        ];
    }
}
