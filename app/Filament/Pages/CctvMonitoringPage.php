<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CctvMonitoringPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?string $title = 'Live CCTV Monitoring';

    protected static string $view = 'filament.pages.cctv-monitoring-page';

    // Variabel Filter (Terkoneksi langsung ke form tanpa reload)
    public $selected_vessel = 'MT. Queen Protocol';
    public $start_date;
    public $end_date;
    public $start_time;
    public $end_time;

    public function mount()
    {
        // Default filter ke hari ini
        $this->start_date = Carbon::now()->toDateString();
        $this->end_date = Carbon::now()->toDateString();
        $this->start_time = '00:00';
        $this->end_time = '23:59';
    }

    protected function getViewData(): array
    {
        // 1. Ambil daftar kapal unik untuk Dropdown
        $daftar_kapal = DB::table('cctv_reports')->select('vessels')->distinct()->get();

        // 2. Format Waktu Pencarian
        $start_datetime = $this->start_date . ' ' . $this->start_time . ':00';
        $end_datetime = $this->end_date . ' ' . $this->end_time . ':59';

        // 3. Ambil data gambar berdasarkan filter
        $raw_data = DB::table('cctv_reports')
            ->where('vessels', $this->selected_vessel)
            ->whereBetween('captured_at', [$start_datetime, $end_datetime])
            ->orderBy('captured_at', 'asc')
            ->get();

        // 4. Kelompokkan berdasarkan Channel (CH 1, CH 2, dst)
        $data_per_channel = $raw_data->groupBy('channel');

        // Ambil nama-nama channel yang ada
        $channels = $data_per_channel->keys()->toArray();

        return [
            'daftar_kapal' => $daftar_kapal,
            'channels' => $channels,
            'data_per_channel' => $data_per_channel,
        ];
    }
}
