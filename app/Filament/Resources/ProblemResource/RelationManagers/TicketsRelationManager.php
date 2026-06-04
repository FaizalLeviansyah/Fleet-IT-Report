<?php

namespace App\Filament\Resources\ProblemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';
    protected static ?string $title = 'Tiket Terdampak (Incident Tickets)';
    protected static ?string $icon = 'heroicon-o-ticket';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Judul Tiket')
                    ->limit(40),

                Tables\Columns\TextColumn::make('requester.full_name')
                    ->label('Pemohon (User)')
                    ->icon('heroicon-m-user'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Tiket')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1=>'New', 2=>'Assigned', 3=>'Planned', 4=>'Pending', 5=>'Solved', 6=>'Closed', default=>'Unknown'
                    })
                    ->color(fn ($state) => match ($state) {
                        1=>'danger', 2=>'warning', 3=>'info', 4=>'gray', 5=>'success', 6=>'success', default=>'gray'
                    }),
            ])
            ->headerActions([
                // FITUR SAKTI: Menautkan tiket yang sudah ada di database ke Problem ini
                Tables\Actions\AttachAction::make()
                    ->label('Tautkan Tiket')
                    ->icon('heroicon-o-link')
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['name'])
                    ->successNotificationTitle('Tiket berhasil ditautkan ke Problem!'),
            ])
            ->actions([
                // Melepas ikatan tiket dari problem
                Tables\Actions\DetachAction::make()
                    ->label('Lepas Tautan')
                    ->successNotificationTitle('Tautan tiket dilepas!'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
