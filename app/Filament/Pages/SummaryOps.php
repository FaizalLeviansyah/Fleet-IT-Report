<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get; // Tambahan untuk menarik data form secara real-time
use Filament\Notifications\Notification;
use App\Models\Laporan;
use Carbon\Carbon;

class SummaryOps extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Summary Ops';
    protected static ?string $title = 'Summary Ops';
    protected static ?string $navigationGroup = 'IT Operation';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.summary-ops';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        $minDate = Laporan::min('waktu_kejadian') ?? now()->subYears(1);

        return $form
            ->schema([
                Section::make('Buat Ringkasan (Summary)')
                    ->description('Pilih rentang tanggal untuk mencetak laporan PDF gabungan armada.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([

                            DatePicker::make('from_date')
                                ->label('DARI TANGGAL')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->format('Y-m-d') // Paksa internal state agar standar
                                ->live()
                                ->minDate($minDate)
                                // 👇 DYNAMIC LOCK: Mentok di hari ini, ATAU mentok di tanggal "to_date"
                                ->maxDate(fn (Get $get) => $get('to_date') ?: now())
                                ->required(),

                            DatePicker::make('to_date')
                                ->label('SAMPAI TANGGAL')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->format('Y-m-d') // Paksa internal state agar standar
                                ->live()
                                // 👇 DYNAMIC LOCK: Tidak bisa mundur dari "from_date"
                                ->minDate(fn (Get $get) => $get('from_date') ?: $minDate)
                                ->maxDate(now())
                                ->required(),

                        ]),
                    ])
            ])
            ->statePath('data');
    }

    public function generatePdf()
    {
        $data = $this->form->getState();

        // Gunakan Carbon::parse karena kita sudah set ->format('Y-m-d')
        $startDate = Carbon::parse($data['from_date'])->startOfDay();
        $endDate = Carbon::parse($data['to_date'])->endOfDay();

        // ZERO-DATA INTERCEPTOR
        $dataCount = Laporan::whereBetween('waktu_kejadian', [$startDate, $endDate])->count();

        if ($dataCount === 0) {
            Notification::make()
                ->title('Data Tidak Ditemukan!')
                ->body('Tidak ada log CCTV yang masuk pada rentang tanggal tersebut. Silakan pilih tanggal lain.')
                ->warning()
                ->duration(6000)
                ->send();

            return;
        }

        Notification::make()
            ->title('Memproses Summary PDF...')
            ->body("Ditemukan {$dataCount} laporan. Menggabungkan data...")
            ->success()
            ->send();

        // Tembak ke Controller PDF (Kembalikan format d/m/Y untuk URL-nya)
        return redirect()->route('cetak.summary', [
            'from' => $startDate->format('d/m/Y'),
            'to' => $endDate->format('d/m/Y')
        ]);
    }
}
