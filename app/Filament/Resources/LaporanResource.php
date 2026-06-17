<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Laporan;
use App\Models\Vessel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan CCTV';
    protected static ?string $navigationGroup = 'IT Operation';
    protected static ?int $navigationSort = 2;

    public static function getFormSchema(bool $useRelationship = true): array
    {
        $repeater = Forms\Components\Repeater::make('gambars')
            ->label('UPLOAD PHOTOS')
            ->schema([
                Forms\Components\FileUpload::make('path_gambar')
                    ->label('Image File')
                    ->image()
                    ->directory('laporan-images')
                    ->required()
                    ->columnSpan(2),

                // 💡 FIX: Samakan Default Fallback
                Forms\Components\Select::make('channel')
                    ->label('CH')
                    ->options(function (Get $get) {
                        $vesselName = $get('../../lokasi');
                        if ($vesselName) {
                            $vessel = Vessel::where('vessel_name', $vesselName)->first();
                            if ($vessel && $vessel->cctv_names) {
                                return $vessel->cctv_names;
                            }
                        }
                        return [
                            'AJG' => 'CCTV 1 (Cam A)',
                            'BRT' => 'CCTV 2 (Cam B)',
                            'CCR' => 'CCTV 3 (Cam C)',
                            'ECR' => 'CCTV 4 (Cam D)',
                            'WKN' => 'CCTV 5 (Cam E)',
                            'WKR' => 'CCTV 6 (Cam F)'
                        ];
                    })
                    ->required()
                    ->columnSpan(1),
            ])->columns(3)->addActionLabel('+ ADD MORE PHOTOS')->defaultItems(0);

        if ($useRelationship) {
            $repeater->relationship('gambars');
        }

        return [
            Forms\Components\Select::make('lokasi')
                ->label('ARMADA / LOKASI')
                ->options(fn () => Vessel::pluck('vessel_name', 'vessel_name')->toArray())
                ->searchable()
                ->live()
                ->required(),

            // 💡 FIX: Samakan Label Toggle Default
            Forms\Components\Section::make('CCTV 6 CH STATUS')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\ToggleButtons::make('status_ajg')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['AJG'] ?? 'CCTV 1 (Cam A)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),

                        Forms\Components\ToggleButtons::make('status_brt')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['BRT'] ?? 'CCTV 2 (Cam B)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),

                        Forms\Components\ToggleButtons::make('status_ccr')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['CCR'] ?? 'CCTV 3 (Cam C)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),

                        Forms\Components\ToggleButtons::make('status_ecr')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['ECR'] ?? 'CCTV 4 (Cam D)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),

                        Forms\Components\ToggleButtons::make('status_wkn')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['WKN'] ?? 'CCTV 5 (Cam E)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),

                        Forms\Components\ToggleButtons::make('status_wkr')
                            ->label(fn (Get $get) => Vessel::where('vessel_name', $get('lokasi'))->first()?->cctv_names['WKR'] ?? 'CCTV 6 (Cam F)')
                            ->options(['Clear' => 'Clear', 'Blur' => 'Blur', 'NA' => 'N/A'])->colors(['Clear' => 'success', 'Blur' => 'warning', 'NA' => 'danger'])->inline()->default('Clear')->required(),
                    ]),
                ]),

            Forms\Components\DateTimePicker::make('waktu_kejadian')->label('TIMESTAMP')->default(now())->required(),
            Forms\Components\Textarea::make('isi_laporan')->label('NARRATIVE')->placeholder('Type details...')->rows(3)->default('Auto-Snapshot'),

            $repeater,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(self::getFormSchema(true))->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('waktu_kejadian', 'desc')
            ->headerActions([
                Action::make('hint_export')
                    ->label('Cara Cetak PDF')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading('Panduan Smart Export PDF')
                    ->modalDescription(new HtmlString('Untuk mencetak PDF, silakan <strong>centang (checklist)</strong> kotak di sebelah kiri pada baris data laporan yang ingin dicetak. Setelah dicentang, tombol <strong>"Bulk Action"</strong> akan muncul di kiri atas tabel, lalu klik dan pilih <strong>"Cetak PDF (Smart Export)"</strong>. Anda bisa mencentang puluhan laporan sekaligus!'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Paham!')
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('gambars.path_gambar')->label('SNAPSHOT')->limit(1)->square()->size(60),
                Tables\Columns\TextColumn::make('lokasi')->label('ARMADA / DATE')->weight('bold')->searchable()->description(fn (Laporan $record): string => $record->waktu_kejadian ? $record->waktu_kejadian->format('d M, H:i T') : '-'),

                Tables\Columns\TextColumn::make('ch_status_badges')->label('6 CH SYSTEMS')->html()->getStateUsing(function (Laporan $record) {
                    $chs = ['AJG' => $record->status_ajg, 'BRT' => $record->status_brt, 'CCR' => $record->status_ccr, 'ECR' => $record->status_ecr, 'WKN' => $record->status_wkn, 'WKR' => $record->status_wkr];

                    // Tarik nama label kustom milik kapal ini, jika belum pernah diubah di Master, gunakan default baru
                    $defaultLabels = [
                        'AJG' => 'CCTV 1 (Cam A)', 'BRT' => 'CCTV 2 (Cam B)', 'CCR' => 'CCTV 3 (Cam C)',
                        'ECR' => 'CCTV 4 (Cam D)', 'WKN' => 'CCTV 5 (Cam E)', 'WKR' => 'CCTV 6 (Cam F)'
                    ];
                    $customLabels = Vessel::where('vessel_name', $record->lokasi)->first()?->cctv_names ?? $defaultLabels;

                    $html = '<div style="display: flex; flex-wrap: wrap; gap: 6px;">';
                    foreach ($chs as $code => $status) {
                        $label = $customLabels[$code] ?? $defaultLabels[$code];

                        $bgColor = match($status) { 'Clear' => '#dcfce7', 'Blur' => '#fef08a', 'NA' => '#fee2e2', default => '#f3f4f6' };
                        $textColor = match($status) { 'Clear' => '#166534', 'Blur' => '#854d0e', 'NA' => '#991b1b', default => '#374151' };

                        $html .= "<span title='Kode Default: {$code}' style='background-color: {$bgColor}; color: {$textColor}; font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; white-space: nowrap;'>{$label}</span>";
                    }
                    return $html . '</div>';
                }),
                Tables\Columns\TextColumn::make('isi_laporan')->label('NARRATIVE')->limit(30)->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label('')
                    ->tooltip('Duplicate Data')
                    ->icon('heroicon-m-document-duplicate')
                    ->color('warning')
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->modalHeading('Duplicate Laporan')
                    ->form(self::getFormSchema(false))
                    ->fillForm(function (Laporan $record) {
                        $data = $record->toArray();
                        $data['waktu_kejadian'] = now();
                        $data['gambars'] = $record->gambars->map(fn($g) => ['path_gambar' => $g->path_gambar, 'channel' => $g->channel])->toArray();
                        return $data;
                    })
                    ->action(function (array $data) {
                        $laporan = Laporan::create(\Illuminate\Support\Arr::except($data, ['gambars']));
                        if (!empty($data['gambars'])) {
                            foreach($data['gambars'] as $g) {
                                $laporan->gambars()->create(['channel' => $g['channel'], 'path_gambar' => $g['path_gambar'], 'is_visible' => true]);
                            }
                        }
                        Notification::make()->title('Data Berhasil Diduplikat!')->success()->send();
                    }),

                Tables\Actions\EditAction::make()->label('')->tooltip('Update Report')->slideOver()->modalWidth('2xl'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('cetak_pdf_smart')
                        ->label('Cetak PDF (Smart Export)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $key = 'export_pdf_' . uniqid();
                            Cache::put($key, $records->pluck('id')->toArray(), now()->addMinutes(5));
                            return redirect()->route('cetak.bulk.laporan', ['key' => $key]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }
}
