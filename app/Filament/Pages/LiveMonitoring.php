<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\Vessel;
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
        // Set default rentang bulan ini saja agar tidak berat
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->start_time = '00:00';
        $this->end_time = '23:59';
        $this->channel_labels = $this->default_labels;
    }

    public function updatedChannelLabels($value, $key)
    {
        if (empty($this->selected_vessel)) {
            Notification::make()->title('Pilih armada terlebih dahulu!')->warning()->send();
            return;
        }

        $vessel = Vessel::where('vessel_name', $this->selected_vessel)->first();
        if ($vessel) {
            $vessel->update(['cctv_names' => $this->channel_labels]);
            Notification::make()
                ->title('Label Nama Kamera Tersimpan di Master Data!')
                ->success()
                ->send();
        }
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

    // 💡 FUNGSI BARU: Smart Query Filter (Anti Bug Eternal Oil 1 vs 2)
    private function applyVesselFilter($query, $vesselName)
    {
        $variants = $this->getVesselVariants($vesselName);

        // Deteksi apakah user sedang mencari Kapal "1" (Romawi I atau Angka 1)
        $isVesselOne = preg_match('/ I$/i', $vesselName) || preg_match('/ 1$/i', $vesselName);

        $query->where(function($q) use ($vesselName, $variants, $isVesselOne) {
            $q->where('vessel_name', $vesselName);

            foreach ($variants as $variant) {
                $q->orWhere(function($subQ) use ($variant, $isVesselOne) {
                    $subQ->where(function($q2) use ($variant) {
                        $q2->where('vessel_name', 'LIKE', "%{$variant}%")
                           ->orWhere('image_path', 'LIKE', "%{$variant}%");
                    });

                    // Jika mencari kapal I, BLOKIR mutlak semua data yang berbau kapal II
                    if ($isVesselOne) {
                        $subQ->where('vessel_name', 'NOT LIKE', "%II%")
                             ->where('image_path', 'NOT LIKE', "%II%")
                             ->where('vessel_name', 'NOT LIKE', "% 2%")
                             ->where('image_path', 'NOT LIKE', "% 2%");
                    }
                });
            }
        });

        return $query;
    }

    public function updatedSelectedVessel($value)
    {
        if (!empty($value)) {
            $vessel = Vessel::where('vessel_name', $value)->first();
            $this->channel_labels = $vessel?->cctv_names ?? $this->default_labels;

            // Menggunakan Smart Query Filter
            $latest = DB::table('cctv_reports');
            $latest = $this->applyVesselFilter($latest, $value)->latest('captured_at')->first();

            if ($latest) {
                // 💡 SMART UX: Otomatis melompat (Time-Travel) ke tanggal snapshot terbaru!
                $latestDate = Carbon::parse($latest->captured_at);
                $this->end_date = $latestDate->format('Y-m-d');
                $this->start_date = $latestDate->copy()->subDays(2)->format('Y-m-d'); // Mundur 2 hari untuk jangkauan
                $this->start_time = '00:00';
                $this->end_time = '23:59';

                Notification::make()
                    ->title('Sistem Terhubung 🟢')
                    ->body("Menampilkan snapshot mutakhir ({$latestDate->format('d M Y')}) dari {$value}.")
                    ->success()
                    ->send();
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
        $daftar_kapal = Vessel::select('vessel_name')->orderBy('vessel_name', 'asc')->get();
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

        // Tarik data dengan Smart Query Filter
        $raw_data = DB::table('cctv_reports');
        $raw_data = $this->applyVesselFilter($raw_data, $this->selected_vessel);
        $raw_data = $raw_data->whereBetween('captured_at', [$start, $end])
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

        $latest = DB::table('cctv_reports');
        $latest = $this->applyVesselFilter($latest, $this->selected_vessel)->orderBy('captured_at', 'desc')->first();

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
