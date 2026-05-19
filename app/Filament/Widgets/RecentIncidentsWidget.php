<?php

namespace App\Filament\Widgets;

use App\Models\IncidentReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class RecentIncidentsWidget extends BaseWidget
{
    protected static ?int $sort = 2; 
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '🚨 Insiden Terakhir & SLA Monitor (Top 5)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                IncidentReport::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->weight('bold')
                    ->searchable()
                    // LOGIKA SLA PINTAR:
                    ->description(function (IncidentReport $record): string {
                        if (in_array($record->status, ['Open', 'In Progress'])) {
                            $hours = $record->created_at->diffInHours(now());
                            if ($hours >= 48) {
                                return '⚠️ OVERDUE (' . $hours . ' Jam)';
                            }
                            return '⏳ SLA Aman (' . $hours . ' Jam)';
                        }
                        return '✅ Diselesaikan';
                    })
                    ->color(function (IncidentReport $record): string {
                        if (in_array($record->status, ['Open', 'In Progress']) && $record->created_at->diffInHours(now()) >= 48) {
                            return 'danger'; // Merah jika telat!
                        }
                        return 'gray';
                    }),
                    
                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Lokasi / Kapal'),
                    
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger',
                        'In Progress' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'gray',
                        default => 'primary',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dilaporkan Pada')
                    ->dateTime('d M Y, H:i'),
            ])
            ->paginated(false); 
    }
}