<?php

namespace App\Filament\Widgets;

use App\Models\IncidentReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentIncidentsWidget extends BaseWidget
{
    protected static ?int $sort = 3; // Urutan paling bawah
    protected int | string | array $columnSpan = 'full'; // Mengambil lebar layar penuh
    protected static ?string $pollingInterval = '10s'; // Auto-refresh 10 detik

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Mengambil 5 tiket terbaru saja
                IncidentReport::query()->latest()->limit(5)
            )
            ->heading('🚨 5 Insiden Terbaru (Live Monitor)')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Masalah')
                    ->limit(40),

                Tables\Columns\TextColumn::make('location_type')
                    ->label('Lokasi')
                    ->getStateUsing(function (IncidentReport $record) {
                        return $record->location_type === 'Kantor'
                            ? $record->office_name
                            : $record->vessel_name;
                    })
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Critical' => 'danger',
                        'High' => 'warning',
                        'Medium' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger',
                        'In Progress' => 'warning',
                        'Resolved' => 'success',
                        'Closed' => 'gray',
                        default => 'primary',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Lapor')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                // Tombol pintasan langsung melompat ke halaman ITSM
                Tables\Actions\Action::make('Lihat Semua')
                    ->url(fn (): string => url('/admin/incident-reports'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
            ])
            ->paginated(false); // Matikan nomor halaman karena ini cuma Top 5
    }
}
