<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiveMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'Live CCTV Monitoring';
    protected static string $view = 'filament.pages.live-monitoring';

    public $selected_vessel = '';
    public $start_date = '';
    public $end_date = '';
    public $start_time = '';
    public $end_time = '';

    public $last_sync = '-';
    public $total_active_cams = 0;

    public $channel_labels = [
        'AJG' => 'AJG (Anjungan)',
        'BRT' => 'BRT (Buritan)',
        'CCR' => 'CCR (Cargo Control Room)',
        'ECR' => 'ECR (Engine Control Room)',
        'WKN' => 'WKN (Wing Kanan)',
        'WKR' => 'WKR (Wing Kiri)',
    ];

    // 🔥 FITUR BARU: SOFTWARE REMAPPER (Memperbaiki kabel DVR yang tertukar di Kapal)
    public $camera_remapper = [
        'MT. Queen Protocol' => [
            // Format: 'Label_Dari_Python' => 'Seharusnya_Tampil_Sebagai_Apa'
            'WKN' => 'BRT', // Python kirim WKN, tapi kita tahu fisiknya itu Buritan
            'BRT' => 'WKN', // Kita tukar posisinya
        ],
        // Tambahkan kapal lain di sini jika ada yang tertukar kabelnya
        // 'MT. Soviana' => ['AJG' => 'CCR', 'CCR' => 'AJG'],
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

        $latest = DB::table('cctv_reports')->where('vessel_name', $this->selected_vessel)->orderBy('captured_at', 'desc')->first();
        $this->last_sync = $latest ? Carbon::parse($latest->captured_at)->format('d-m-Y H:i') : 'Tidak Ada Data';
        $this->total_active_cams = $raw_data->unique('channel')->count();

        // LOGIKA PENUKARAN (REMAPPER) EKSKUSI
        $grouped = $raw_data->groupBy('channel');
        foreach ($grouped as $channel => $data) {

            // Cek apakah kapal ini punya aturan remapper?
            $actual_channel = $channel;
            if (isset($this->camera_remapper[$this->selected_vessel][$channel])) {
                $actual_channel = $this->camera_remapper[$this->selected_vessel][$channel];
            }

            if (in_array($actual_channel, $standard_channels)) {
                // Jika data sudah ada, gabungkan (merge) untuk jaga-jaga
                $data_per_channel[$actual_channel] = $data_per_channel[$actual_channel]->merge($data)->sortBy('captured_at')->values();
            } else {
                $standard_channels[] = $actual_channel;
                $data_per_channel[$actual_channel] = $data->sortBy('captured_at')->values();
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
