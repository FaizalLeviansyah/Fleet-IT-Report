<?php

namespace App\Filament\Widgets;

use App\Models\LaporanPegawai;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Saade\FilamentFullCalendar\Data\EventData;

class LaporanPegawaiCalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 4; // Tampil di Dashboard paling bawah
    public string|null|\Illuminate\Database\Eloquent\Model $model = LaporanPegawai::class;

    // 1. Fungsi Menarik Data ke Kalender
    public function fetchEvents(array $fetchInfo): array
    {
        return LaporanPegawai::where('waktu_mulai', '>=', $fetchInfo['start'])
            ->where('waktu_selesai', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (LaporanPegawai $laporan) {
                return EventData::make()
                    ->id($laporan->id)
                    ->title($laporan->judul_laporan . ' (' . $laporan->nama_pegawai . ')')
                    ->start($laporan->waktu_mulai)
                    ->end($laporan->waktu_selesai)
                    ->backgroundColor('#0F4C81'); // Warna biru elegan
            })
            ->toArray();
    }

    // 2. Fungsi Form Cerdas (Ubah jadi PUBLIC)
    public function getFormSchema(): array
    {
        return [
            Grid::make(2)->schema([
                TextInput::make('judul_laporan')
                    ->label('Judul Laporan')
                    ->required(),

                TextInput::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->required(),

                DateTimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->required(),

                DateTimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->required(),

                Textarea::make('deskripsi_pekerjaan')
                    ->label('Deskripsi Pekerjaan / Hasil')
                    ->columnSpanFull()
                    ->required(),
            ]),
        ];
    }
}
