<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;

class SummaryOps extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Summary Ops';
    protected static ?string $title = 'Summary Ops';

    // Pastikan ejaannya persis seperti di Provider
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
        return $form
            ->schema([
                // Menggunakan Native Section Filament (Anti-Hancur & Kalender Aman)
                Section::make('Buat Ringkasan (Summary)')
                    ->description('Pilih rentang tanggal untuk mencetak laporan PDF gabungan armada.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            DatePicker::make('from_date')
                                ->label('DARI TANGGAL')
                                ->native(false) // Tampilan kalender modern Filament
                                ->displayFormat('d/m/Y')
                                ->required(),

                            DatePicker::make('to_date')
                                ->label('SAMPAI TANGGAL')
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->required(),
                        ]),
                    ])
            ])
            ->statePath('data');
    }

    public function generatePdf()
    {
        $data = $this->form->getState();

        Notification::make()
            ->title('Memproses Summary PDF...')
            ->body('Menggabungkan data dari ' . $data['from_date'] . ' sampai ' . $data['to_date'])
            ->success()
            ->send();

        // Targetkan fungsi cetak Anda di sini nantinya
    }
}
