<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FollowupsRelationManager extends RelationManager
{
    protected static string $relationship = 'followups';
    protected static ?string $title = 'Tindak Lanjut (Follow-ups)';
    protected static ?string $icon = 'heroicon-o-chat-bubble-left-right';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Otomatis assign siapa yang membalas komentar ini
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->user()->employee_id ?? auth()->id()),

                Forms\Components\RichEditor::make('content')
                    ->label('Pesan / Komentar')
                    ->placeholder('Tulis tindak lanjut atau balasan untuk tiket ini...')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_private')
                    ->label('Private (Hanya Teknisi yang bisa lihat)')
                    ->default(false)
                    ->onColor('warning'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Aktor')
                    ->icon('heroicon-m-user-circle')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('content')
                    ->label('Pesan')
                    ->html()
                    ->wrap(),

                Tables\Columns\IconColumn::make('is_private')
                    ->label('Private')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-globe-americas')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Balasan')->icon('heroicon-o-plus')
                    ->successNotificationTitle('Komentar berhasil dikirim!'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->successNotificationTitle('Komentar diperbarui!'),
                Tables\Actions\DeleteAction::make()->successNotificationTitle('Komentar dihapus!'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
