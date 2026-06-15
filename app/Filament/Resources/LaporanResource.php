<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan CCTV';
    protected static ?string $navigationGroup = 'CCTV MONITORING';
    protected static ?int $navigationSort = 2;

    // Schema bersih agar bisa dipakai di Create, Edit, dan Duplicate
    public static function getFormSchema(bool $useRelationship = true): array
    {
        $repeater = Forms\Components\Repeater::make('gambars')
            ->label('UPLOAD PHOTOS')
            ->schema([
                Forms\Components\FileUpload::make('path_gambar')->label('Image File')->image()->directory('laporan-images')->required()->columnSpan(2),
                Forms\Components\Select::make('channel')->label('CH')->options(['AJG'=>'AJG','BRT'=>'BRT','CCR'=>'CCR','ECR'=>'ECR','WKN'=>'WKN','WKR'=>'WKR'])->required()->columnSpan(1),
            ])->columns(3)->addActionLabel('+ ADD MORE PHOTOS')->defaultItems(0);

        if ($useRelationship) {
            $repeater->relationship('gambars');
        }

        return [
            Forms\Components\Select::make('lokasi')->label('ARMADA / LOKASI')->options(fn () => \App\Models\Vessel::pluck('vessel_name', 'vessel_name')->toArray())->searchable()->required(),

            Forms\Components\Section::make('CCTV 6 CH STATUS')->schema([
                Forms\Components\Select::make('status_ajg')->label('AJG')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
                Forms\Components\Select::make('status_brt')->label('BRT')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
                Forms\Components\Select::make('status_ccr')->label('CCR')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
                Forms\Components\Select::make('status_ecr')->label('ECR')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
                Forms\Components\Select::make('status_wkn')->label('WKN')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
                Forms\Components\Select::make('status_wkr')->label('WKR')->options(['Clear' => '🟢 Clear', 'Blur' => '🟡 Blur', 'NA' => '🔴 NA'])->default('Clear')->native(false),
            ])->columns(2),

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
            ->columns([
                Tables\Columns\ImageColumn::make('gambars.path_gambar')->label('SNAPSHOT')->limit(1)->square()->size(60),
                Tables\Columns\TextColumn::make('lokasi')->label('ARMADA / DATE')->weight('bold')->searchable()->description(fn (Laporan $record): string => $record->waktu_kejadian ? $record->waktu_kejadian->format('d M, H:i T') : '-'),

                Tables\Columns\TextColumn::make('ch_status_badges')->label('6 CH SYSTEMS')->html()->getStateUsing(function (Laporan $record) {
                    $chs = ['AJG' => $record->status_ajg, 'BRT' => $record->status_brt, 'CCR' => $record->status_ccr, 'ECR' => $record->status_ecr, 'WKN' => $record->status_wkn, 'WKR' => $record->status_wkr];
                    $html = '<div style="display: flex; flex-wrap: wrap; gap: 4px; width: 140px;">';
                    foreach ($chs as $label => $status) {
                        $bgColor = match($status) { 'Clear' => '#dcfce7', 'Blur' => '#fef08a', 'NA' => '#fee2e2', default => '#f3f4f6' };
                        $textColor = match($status) { 'Clear' => '#166534', 'Blur' => '#854d0e', 'NA' => '#991b1b', default => '#374151' };
                        $html .= "<span style='background-color: {$bgColor}; color: {$textColor}; font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 4px; width: 40px; text-align: center;'>{$label}</span>";
                    }
                    return $html . '</div>';
                }),
                Tables\Columns\TextColumn::make('isi_laporan')->label('NARRATIVE')->limit(30)->color('gray'),
            ])
            ->actions([
                // AKSI DUPLIKAT SPA (Slide-Over Panel)
                Tables\Actions\Action::make('duplicate')
                    ->label('')
                    ->tooltip('Duplicate Data')
                    ->icon('heroicon-m-document-duplicate')
                    ->color('warning')
                    ->slideOver()
                    ->modalWidth('2xl')
                    ->modalHeading('Duplicate Laporan')
                    ->form(self::getFormSchema(false)) // Tarik form tanpa relasi (khusus input duplikat)
                    ->fillForm(function (Laporan $record) {
                        $data = $record->toArray();
                        $data['waktu_kejadian'] = now(); // Update jam terbaru
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

                // AKSI EDIT SPA (Slide-Over Panel)
                Tables\Actions\EditAction::make()->label('')->tooltip('Update Report')->slideOver()->modalWidth('2xl'),
                Tables\Actions\DeleteAction::make()->label('')->tooltip('Delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    \Filament\Tables\Actions\ExportBulkAction::make()->label('Export Selected')->icon('heroicon-o-document-arrow-down')->color('danger'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            // Semua aksi (Create, Edit) sekarang menggunakan Modal/SlideOver di halaman Index!
        ];
    }
}
