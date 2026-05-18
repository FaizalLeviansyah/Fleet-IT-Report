<?php

namespace App\Filament\Widgets;

use App\Models\IncidentReport;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Data\EventData;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;

class VesselDowntimeCalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 4;
    protected static ?string $heading = 'Kalender Pantauan Downtime Kapal';

    public string|null|\Illuminate\Database\Eloquent\Model $model = IncidentReport::class;

    public function fetchEvents(array $fetchInfo): array
    {
        // Tarik semua insiden yang terkait dengan kapal
        return IncidentReport::whereNotNull('vessel_name')
            ->where('created_at', '>=', $fetchInfo['start'])
            ->get()
            ->map(function (IncidentReport $incident) {

                // Logika Pintar: Jika Open/In Progress = Merah (Downtime). Jika Resolved/Closed = Hijau (Uptime)
                $isDowntime = in_array($incident->status, ['Open', 'In Progress']);
                $color = $isDowntime ? '#ef4444' : '#10b981'; // Merah atau Hijau

                // Gunakan updated_at sebagai waktu selesai jika tiket ditutup, atau waktu saat ini jika masih open
                $endTime = $isDowntime ? now() : $incident->updated_at;

                return EventData::make()
                    ->id($incident->id)
                    ->title($incident->vessel_name . ' - ' . $incident->category)
                    ->start($incident->created_at)
                    ->end($endTime)
                    ->backgroundColor($color);
            })
            ->toArray();
    }

    // Form saat jadwal di kalender diklik (Read-only untuk melihat sekilas)
    public function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('ticket_number')->label('No Tiket')->readOnly(),
                TextInput::make('vessel_name')->label('Nama Kapal')->readOnly(),
                TextInput::make('title')->label('Judul Insiden')->columnSpanFull()->readOnly(),
                TextInput::make('status')->label('Status')->readOnly(),
            ]),
        ];
    }
}
