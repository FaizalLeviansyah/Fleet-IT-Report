<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiveMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'CCTV Monitoring';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'CCTV Monitoring';
    protected static string $view = 'filament.pages.live-monitoring';

    public $selected_vessel = '';
    public $start_date = '';
    public $end_date = '';
    public $start_time = '';
    public $end_time = '';

    // 🔥 FITUR BARU: PILIHAN INTERVAL FRAME SLIDESHOW
    public $frame_interval = 'all';

    public $last_sync = '-';
    public $total_active_cams = 0;

    public $channel_labels = [
        'AJG' => 'CCTV 1 (Cam A)',
        'BRT' => 'CCTV 2 (Cam B)',
        'CCR' => 'CCTV 3 (Cam C)',
        'ECR' => 'CCTV 4 (Cam D)',
        'WKN' => 'CCTV 5 (Cam E)',
        'WKR' => 'CCTV 6 (Cam F)',
    ];

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->toDateString();
        $this->end_date = Carbon::now()->toDateString();
        $this->start_time = '00:00';
        $this->end_time = '23:59';
    }

    public function updatedSelectedVessel($value)
    {
        if (!empty($value)) {
            $latest = DB::table('cctv_reports')->where('vessel_name', $value)->latest('captured_at')->first();
            if ($latest) {
                Notification::make()->title('Sistem Terhubung 🟢')->body("Mengambil data {$value}.")->success()->send();
            } else {
                Notification::make()->title('Kapal Offline 🔴')->body("Belum ada data untuk {$value}.")->danger()->send();
            }
        }
    }

    // Notifikasi saat Transisi / Interval diubah
    public function updatedFrameInterval($value)
    {
        $labels = [
            'all' => 'Semua Frame (Realtime)',
            'hourly' => 'Per 1 Jam',
            'half_day' => 'Per 12 Jam (AM/PM)',
            'daily' => 'Per Hari'
        ];
        Notification::make()->title('Kecepatan Frame Diubah')->body("Menampilkan interval: " . $labels[$value])->success()->send();
    }

    public function applyFilter()
    {
        Notification::make()->title('Filter Diterapkan')->body('Mensinkronkan timeline CCTV...')->info()->send();
    }

    protected function getViewData(): array
    {
        $daftar_kapal = DB::table('vessels')->select('vessel_name')->orderBy('vessel_name', 'asc')->get();
        $standard_channels = ['AJG', 'BRT', 'CCR', 'ECR', 'WKN', 'WKR'];
        $data_per_channel = array_fill_keys($standard_channels, collect());

        if (empty($this->selected_vessel)) {
            return [
                'daftar_kapal' => $daftar_kapal,
                'channels' => $standard_channels,
                'data_per_channel' => $data_per_channel,
                'channel_labels' => $this->channel_labels,
            ];
        }

        $start = Carbon::parse($this->start_date . ' ' . $this->start_time)->format('Y-m-d H:i:s');
        $end = Carbon::parse($this->end_date . ' ' . $this->end_time)->format('Y-m-d H:i:s');

        $raw_data = DB::table('cctv_reports')
            ->where('vessel_name', $this->selected_vessel)
            ->whereBetween('captured_at', [$start, $end])
            ->orderBy('captured_at', 'asc')
            ->get();

        // 🔥 LOGIKA PENYARINGAN INTERVAL (JAM/HARI)
        if ($this->frame_interval !== 'all') {
            $raw_data = $raw_data->groupBy(function($item) {
                $time = Carbon::parse($item->captured_at);
                $intervalKey = $time->format('Y-m-d H'); // default Per Jam
                if ($this->frame_interval === 'half_day') $intervalKey = $time->format('Y-m-d A'); // AM / PM
                if ($this->frame_interval === 'daily') $intervalKey = $time->format('Y-m-d'); // Per Hari

                // Pisahkan berdasarkan Channel agar kamera tidak saling tertukar
                return $item->channel . '_' . $intervalKey;
            })->map(function($group) {
                return $group->first(); // Ambil 1 foto pertama dari rentang waktu tersebut
            })->values();
        }

        $latest = DB::table('cctv_reports')->where('vessel_name', $this->selected_vessel)->orderBy('captured_at', 'desc')->first();
        $this->last_sync = $latest ? Carbon::parse($latest->captured_at)->format('d M Y - h:i A') : 'Tidak Ada Data';
        $this->total_active_cams = $raw_data->unique('channel')->count();

        $grouped = $raw_data->groupBy('channel');
        foreach ($grouped as $channel => $data) {
            if (in_array($channel, $standard_channels)) {
                $data_per_channel[$channel] = $data->sortBy('captured_at')->values();
            } else {
                $standard_channels[] = $channel;
                $data_per_channel[$channel] = $data->sortBy('captured_at')->values();
            }
        }

        return [
            'daftar_kapal' => $daftar_kapal,
            'channels' => $standard_channels,
            'data_per_channel' => $data_per_channel,
            'channel_labels' => $this->channel_labels,
        ];
    }
}
