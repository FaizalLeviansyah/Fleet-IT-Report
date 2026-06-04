<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPendingTickets extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full'; // Membentang penuh
    protected static ?string $pollingInterval = '10s'; // Auto-refresh

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::whereIn('status', [1, 2])->latest()->limit(5)
            )
            ->heading('🚨 5 Tiket Kritis Membutuhkan Penanganan (Live)')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Judul Insiden')
                    ->limit(40)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('requester.full_name')
                    ->label('Pelapor')
                    ->icon('heroicon-m-user'),

                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Rendah', 2 => 'Sedang', 3 => 'Tinggi', 4 => 'Sangat Tinggi', 5 => 'Kritis', default => 'Unknown'
                    })
                    ->colors(['success' => 1, 'warning' => 2, 'danger' => 3, 'danger' => 4, 'danger' => 5]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->since()
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Tables\Actions\Action::make('tangani')
                    ->label('Buka Tiket')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Ticket $record): string => \App\Filament\Resources\TicketResource::getUrl('edit', ['record' => $record]))
                    ->button(),
            ])
            ->paginated(false); // Matikan pagination karena ini hanya Top 5
    }
}
