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

    // Properti Form
    public $selected_vessel = '';
    public $start_date = '';
    public $end_date = '';
    public $start_time = '';
    public $end_time = '';

    // Properti Widget Tambahan
    public $last_sync = '-';
    public $total_active_cams = 0;

    // Mapping Nama Lengkap CCTV (Termasuk antisipasi data Seeder CH-01)
    public $channel_labels = [
        'AJG' => 'AJG (Anjungan)',
        'BRT' => 'BRT (Buritan)',
        'CCR' => 'CCR (Cargo Control Room)',
        'ECR' => 'ECR (Engine Control Room)',
        'WKN' => 'WKN (Wing Kanan)',
        'WKR' => 'WKR (Wing Kiri)',
        'CH-01' => 'CH-01 (Kamera 1)', // Fallback data dummy
        'CH-02' => 'CH-02 (Kamera 2)',
        'CH-03' => 'CH-03 (Kamera 3)',
        'CH-04' => 'CH-04 (Kamera 4)',
        'CH-05' => 'CH-05 (Kamera 5)',
        'CH-06' => 'CH-06 (Kamera 6)',
    ];

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->toDateString();
        $this->end_date = Carbon::now()->toDateString();
        $this->start_time = '00:00';
        $this->end_time = '23:59';
    }

    // 🔥 FITUR REALTIME: Terpicu otomatis saat Dropdown Kapal diganti (wire:model.live)
    public function updatedSelectedVessel($value)
    {
        if (!empty($value)) {
            $latest = DB::table('cctv_reports')->where('vessel_name', $value)->latest('captured_at')->first();

            if ($latest) {
                Notification::make()
                    ->title('Kapal Online 🟢')
                    ->body("Tersambung ke {$value}. Data CCTV tersedia.")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Kapal Offline / Kosong 🔴')
                    ->body("Belum ada riwayat snapshot untuk {$value}.")
                    ->danger()
                    ->send();
            }
        }
    }

    // Terpicu saat tombol APPLY ditekan
    public function applyFilter()
    {
        Notification::make()
            ->title('Filter Diterapkan')
            ->body('Mencari rekaman CCTV...')
            ->info()
            ->send();
    }

    protected function getViewData(): array
    {
        $daftar_kapal = DB::table('vessels')->select('vessel_name')->orderBy('vessel_name', 'asc')->get();
        $standard_channels = ['AJG', 'BRT', 'CCR', 'ECR', 'WKN', 'WKR'];
        $data_per_channel = array_fill_keys($standard_channels, collect());

        if (empty($this->selected_vessel)) {
            $this->last_sync = '-';
            $this->total_active_cams = 0;
            return [
                'daftar_kapal' => $daftar_kapal,
                'channels' => $standard_channels,
                'data_per_channel' => $data_per_channel,
                'channel_labels' => $this->channel_labels,
            ];
        }

        // PERBAIKAN FATAL BUG TANGGAL (Menggunakan Parse Carbon yang aman)
        $start = Carbon::parse($this->start_date . ' ' . $this->start_time)->format('Y-m-d H:i:s');
        $end = Carbon::parse($this->end_date . ' ' . $this->end_time)->format('Y-m-d H:i:s');

        $raw_data = DB::table('cctv_reports')
            ->where('vessel_name', $this->selected_vessel)
            ->whereBetween('captured_at', [$start, $end])
            ->orderBy('captured_at', 'asc')
            ->get();

        // Update Realtime Widget Stats
        $latest = DB::table('cctv_reports')->where('vessel_name', $this->selected_vessel)->orderBy('captured_at', 'desc')->first();
        $this->last_sync = $latest ? Carbon::parse($latest->captured_at)->format('d-m-Y H:i') : 'Tidak Ada Data';
        $this->total_active_cams = $raw_data->unique('channel')->count();

        // Kelompokkan Data
        $grouped = $raw_data->groupBy('channel');
        foreach ($grouped as $channel => $data) {
            if (in_array($channel, $standard_channels)) {
                $data_per_channel[$channel] = $data;
            } else {
                $standard_channels[] = $channel;
                $data_per_channel[$channel] = $data;
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
