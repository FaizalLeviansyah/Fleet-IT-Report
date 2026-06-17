<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Laporan;
use App\Models\Vessel;
use Filament\Notifications\Notification;

class CatatanVessel extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Catatan Kapal';
    protected static ?string $title = 'Catatan Tambahan Vessel';

    // 👇 Masuk ke dalam rumah IT Operation
    protected static ?string $navigationGroup = 'IT Operation';
    // 💡 SECURITY (RBAC): Sembunyikan page ini dari Client (Owner)
    public static function canAccess(): bool
    {
        return strtolower(auth()->user()->role) !== 'owner';
    }
    protected static ?int $navigationSort = 4; // Taruh paling atas di IT Operation

    protected static string $view = 'filament.pages.catatan-vessel';

    public $vessels = [];
    public $selectedVessel = null;
    public $laporans = [];
    public $notes = []; // Menyimpan ketikan textarea secara real-time

    public function mount()
    {
        // Tarik data master kapal
        $this->vessels = Vessel::pluck('vessel_name', 'vessel_name')->toArray();

        // Pilih kapal pertama secara default jika ada
        if (!empty($this->vessels)) {
            $this->selectedVessel = array_key_first($this->vessels);
            $this->loadLaporans();
        }
    }

    // Fungsi otomatis berjalan saat Dropdown Kapal diganti
    public function updatedSelectedVessel()
    {
        $this->loadLaporans();
    }

    public function loadLaporans()
    {
        if (!$this->selectedVessel) return;

        // Tarik laporan berdasarkan kapal yang dipilih
        $data = Laporan::where('lokasi', $this->selectedVessel)
            ->orderBy('waktu_kejadian', 'desc')
            ->limit(30) // Batasi 30 data terakhir agar ringan
            ->get();

        $this->laporans = $data;

        // Isi textarea dengan data yang sudah ada di database
        foreach($data as $item) {
            $this->notes[$item->id] = $item->catatan_tambahan;
        }
    }

    public function saveNote($id)
    {
        $laporan = Laporan::find($id);

        if ($laporan) {
            $laporan->update([
                'catatan_tambahan' => $this->notes[$id] ?? null
            ]);

            Notification::make()
                ->title('Tersimpan!')
                ->body('Catatan untuk ID #'.$id.' berhasil diperbarui.')
                ->success()
                ->send();
        }
    }
}
