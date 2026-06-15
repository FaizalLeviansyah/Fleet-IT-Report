<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LiveMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'IT Operation';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'CCTV Monitoring';
    protected static string $view = 'filament.pages.live-monitoring';

    public $selected_vessel = '';
    public $start_date = '';
    public $end_date = '';
    public $start_time = '';
    public $end_time = '';
    public $frame_interval = 'all';
    public $last_sync = '-';
    public $total_active_cams = 0;

    public $channel_labels = [];

    // Template default jika armada tersebut belum pernah disetting namanya
    private $default_labels = [
        'AJG' => 'CCTV 1 (Cam A)',
        'BRT' => 'CCTV 2 (Cam B)',
        'CCR' => 'CCTV 3 (Cam C)',
        'ECR' => 'CCTV 4 (Cam D)',
        'WKN' => 'CCTV 5 (Cam E)',
        'WKR' => 'CCTV 6 (Cam F)',
    ];

    public function mount()
    {
        $this->start_date = '2026-01-01';
        $this->end_date = '2026-12-31';
        $this->start_time = '00:00';
        $this->end_time = '23:59';

        $this->channel_labels = $this->default_labels;
    }

    // 💡 SMART SYSTEM: Simpan nama kamera ke Cache KHUSUS untuk kapal yang dipilih
    public function updatedChannelLabels($value, $key)
    {
        if (empty($this->selected_vessel)) {
            Notification::make()->title('Pilih armada terlebih dahulu!')->warning()->send();
            return;
        }

        // Simpan permanen ke cache dengan kunci unik nama kapalnya
        Cache::forever('cctv_labels_' . md5($this->selected_vessel), $this->channel_labels);
        Notification::make()->title('Label Kamera Tersimpan!')->success()->send();
    }

    private function getVesselVariants($vesselName): array
    {
        if (empty($vesselName)) return [];

        return array_filter(array_unique([
            $vesselName,
            str_replace(' ', '_', $vesselName),
            str_replace([' ', '.'], ['_', ''], $vesselName),
            str_replace([' II', ' I'], [' 2', ' 1'], $vesselName),
            str_replace([' ', '.'], ['_', ''], str_replace([' II', ' I'], [' 2', ' 1'], $vesselName)),
            str_replace(' ', '_', str_replace([' II', ' I'], [' 2', ' 1'], $vesselName)),
            preg_replace('/[^A-Za-z0-9]/', '', $vesselName)
        ]), function($v) {
            return strlen($v) > 2 && !in_array(strtoupper($v), ['MT', 'MV', 'MT.', 'MV.']);
        });
    }

    public function updatedSelectedVessel($value)
    {
        if (!empty($value)) {
            // 💡 SMART SYSTEM: Tarik nama kamera unik milik kapal ini saat dropdown diubah
            $this->channel_labels = Cache::get('cctv_labels_' . md5($value), $this->default_labels);

            $variants = $this->getVesselVariants($value);

            $latest = DB::table('cctv_reports')
                ->where(function($q) use ($value, $variants) {
                    $q->where('vessel_name', $value);
                    foreach ($variants as $variant) {
                        $q->orWhere('image_path', 'LIKE', "%{$variant}%");
                    }
                })
                ->latest('captured_at')
                ->first();

            if ($latest) {
                Notification::make()->title('Sistem Terhubung 🟢')->body("Mengambil data lintasan CCTV {$value}.")->success()->send();
            } else {
                Notification::make()->title('Kapal Offline 🔴')->body("Belum ada rekaman fisik untuk {$value}.")->danger()->send();
            }
        } else {
            $this->channel_labels = $this->default_labels;
        }
    }

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

        $variants = $this->getVesselVariants($this->selected_vessel);

        $raw_data = DB::table('cctv_reports')
            ->where(function($q) use ($variants) {
                $q->where('vessel_name', $this->selected_vessel);
                foreach ($variants as $variant) {
                    $q->orWhere('vessel_name', 'LIKE', "%{$variant}%")
                      ->orWhere('image_path', 'LIKE', "%{$variant}%");
                }
            })
            ->whereBetween('captured_at', [$start, $end])
            ->orderBy('captured_at', 'asc')
            ->get();

        if ($this->frame_interval !== 'all') {
            $raw_data = $raw_data->groupBy(function($item) {
                $time = Carbon::parse($item->captured_at);
                $intervalKey = $time->format('Y-m-d H');
                if ($this->frame_interval === 'half_day') $intervalKey = $time->format('Y-m-d A');
                if ($this->frame_interval === 'daily') $intervalKey = $time->format('Y-m-d');
                return $item->channel . '_' . $intervalKey;
            })->map(function($group) {
                return $group->first();
            })->values();
        }

        $latest = DB::table('cctv_reports')
            ->where(function($q) use ($variants) {
                $q->where('vessel_name', $this->selected_vessel);
                foreach ($variants as $variant) {
                    $q->orWhere('image_path', 'LIKE', "%{$variant}%");
                }
            })
            ->orderBy('captured_at', 'desc')
            ->first();

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
