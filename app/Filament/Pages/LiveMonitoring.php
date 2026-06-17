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
    public $min_date = '';
    public $max_date = '';
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
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->start_time = '00:00';
        $this->end_time = '23:59';
        $this->channel_labels = $this->default_labels;
    }

    // 💡 SECURITY: Cegah Owner mengedit nama kamera di Live Monitoring (Opsional tapi aman)
    public function updatedChannelLabels($value, $key)
    {
        if (auth()->user()->role === 'owner') {
            Notification::make()->title('Akses Ditolak!')->body('Hanya Admin yang dapat merubah label kamera.')->danger()->send();
            // Kembalikan data lama
            $vessel = Vessel::where('vessel_name', $this->selected_vessel)->first();
            $this->channel_labels = $vessel?->cctv_names ?? $this->default_labels;
            return;
        }

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

    private function applyVesselFilter($query, $vesselName)
    {
        $variants = $this->getVesselVariants($vesselName);
        $isVesselOne = preg_match('/ I$/i', $vesselName) || preg_match('/ 1$/i', $vesselName);

        $query->where(function($q) use ($vesselName, $variants, $isVesselOne) {
            $q->where('vessel_name', $vesselName);

            foreach ($variants as $variant) {
                $q->orWhere(function($subQ) use ($variant, $isVesselOne) {
                    $subQ->where(function($q2) use ($variant) {
                        $q2->where('vessel_name', 'LIKE', "%{$variant}%")
                           ->orWhere('image_path', 'LIKE', "%{$variant}%");
                    });

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

            $latestQuery = DB::table('cctv_reports');
            $latest = $this->applyVesselFilter($latestQuery, $value)->latest('captured_at')->first();

            $oldestQuery = DB::table('cctv_reports');
            $oldest = $this->applyVesselFilter($oldestQuery, $value)->oldest('captured_at')->first();

            if ($latest && $oldest) {
                $latestDate = Carbon::parse($latest->captured_at);

                $this->max_date = $latestDate->format('Y-m-d');
                $this->min_date = Carbon::parse($oldest->captured_at)->format('Y-m-d');

                $this->end_date = $this->max_date;
                $this->start_date = $latestDate->copy()->subDays(2)->format('Y-m-d');
                $this->start_time = '00:00';
                $this->end_time = '23:59';

                Notification::make()
                    ->title('Sistem Terhubung 🟢')
                    ->body("Tersedia data dari {$this->min_date} s/d {$this->max_date}.")
                    ->success()
                    ->send();
            } else {
                $this->min_date = '';
                $this->max_date = '';
                Notification::make()->title('Kapal Offline 🔴')->body("Belum ada rekaman fisik untuk {$value}.")->danger()->send();
            }
        } else {
            $this->channel_labels = $this->default_labels;
            $this->min_date = '';
            $this->max_date = '';
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
        $user = auth()->user();

        // 💡 SECURITY (MULTI-TENANT): Filter Kapal di Dropdown Live Monitoring
        $vesselQuery = Vessel::select('vessel_name')->orderBy('vessel_name', 'asc');
        if ($user->role === 'owner') {
            $vesselQuery->where('company_name', $user->company);
        }
        $daftar_kapal = $vesselQuery->get();

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
