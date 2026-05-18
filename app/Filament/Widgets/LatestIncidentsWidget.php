<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\IncidentReport;

class LatestIncidentsWidget extends BaseWidget
{
    protected static ?int $sort = 3; // Tampil paling bawah
    protected int | string | array $columnSpan = 'full'; // Membentang lebar

    public function table(Table $table): Table
    {
        return $table
            ->query(IncidentReport::query()->latest()->limit(5)) // Ambil 5 terbaru
            ->heading('5 Insiden Terakhir')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')->label('No. Tiket')->weight('bold'),
                Tables\Columns\TextColumn::make('title')->label('Insiden'),
                Tables\Columns\TextColumn::make('priority')->badge()
                    ->color(fn (string $state): string => match ($state) { 'Critical' => 'danger', 'High' => 'warning', default => 'gray'}),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) { 'Open' => 'danger', 'Resolved' => 'success', default => 'warning'}),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y, H:i'),
            ]);
    }
}
