<?php

namespace App\Filament\Resources\IncidentReportResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ThreadsRelationManager extends RelationManager
{
    protected static string $relationship = 'threads';
    protected static ?string $title = 'Follow-ups / Diskusi Tiket';
    protected static ?string $icon = 'heroicon-o-chat-bubble-left-right';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Select::make('type')
                    ->label('Tipe Pesan')
                    ->options([
                        'Follow-up' => 'Follow-up (Tindak Lanjut)',
                        'Internal Note' => 'Catatan Internal',
                        'Solution' => 'Solusi',
                    ])->default('Follow-up')->required(),

                // Opsional: Jika mau mencatat ID User yang login, gunakan auth()->id() di background.
                // Untuk sekarang kita set null/kosong atau isi manual jika belum ada SSO.
                \Filament\Forms\Components\Hidden::make('user_id')->default(1),

                \Filament\Forms\Components\RichEditor::make('content')
                    ->label('Isi Pesan / Follow-up')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            // SIHIR FILAMENT: Ubah tabel kotak-kotak jadi List/Timeline!
            ->contentGrid(['md' => 1])
            ->columns([
                \Filament\Tables\Columns\Layout\Stack::make([
                    \Filament\Tables\Columns\TextColumn::make('type')
                        ->badge()
                        ->color(fn ($state) => match($state) { 'Solution' => 'success', 'Internal Note' => 'warning', default => 'info' }),

                    \Filament\Tables\Columns\TextColumn::make('created_at')
                        ->dateTime('d M Y, H:i')
                        ->size('sm')
                        ->color('gray'),

                    \Filament\Tables\Columns\TextColumn::make('content')
                        ->html() // Agar format teks Rich Editor (Bold, Italic) terbaca
                        ->extraAttributes(['class' => 'mt-2 p-3 bg-gray-50 rounded-lg border']),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Follow-up')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
