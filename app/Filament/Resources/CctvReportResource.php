<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CctvReportResource\Pages;
use App\Models\CctvReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CctvReportResource extends Resource
{
    protected static ?string $model = CctvReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'IT Operation';
    protected static ?string $navigationLabel = 'Laporan CCTV Vessel';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Laporan Mingguan')
                    ->description('Pilih kapal dan periode pengecekan CCTV.')
                    ->schema([
                        Forms\Components\Select::make('vessel_name')
                            ->label('Nama Kapal (Vessel)')
                            ->options([
                                'Vessel 1' => 'Vessel 1 - MV. Amarin',
                                'Vessel 2' => 'Vessel 2 - MT. Amarin',
                                'Vessel 3' => 'Vessel 3 - TB. Amarin',
                                'Vessel 4' => 'Vessel 4 - BG. Amarin',
                            ])
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('report_date')
                            ->label('Tanggal Pengecekan')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status Keseluruhan')
                            ->options([
                                'Normal' => '✅ Normal (Semua Kamera Aktif)',
                                'Warning' => '⚠️ Warning (Ada Kamera Mati/Blur)',
                                'Critical' => '🚨 Critical (DVR/NVR Mati Total)',
                            ])
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Checklist Kamera (Real-time)')
                    ->description('Catat status setiap kamera pada vessel ini.')
                    ->schema([
                        Forms\Components\Repeater::make('camera_checklist')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('camera_name')
                                    ->label('Nama/Lokasi Kamera')
                                    ->placeholder('Contoh: Engine Room / Deck')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('status')
                                    ->label('Status Kamera')
                                    ->options([
                                        'Online' => '🟢 Online (Jernih)',
                                        'Blur' => '🟡 Blur / Kotor',
                                        'Offline' => '🔴 Offline / No Signal',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('remarks')
                                    ->label('Catatan / Keterangan')
                                    ->placeholder('Tindakan yang diperlukan...')
                                    ->columnSpan(3),
                            ])
                            ->columns(6)
                            ->defaultItems(4)
                            ->addActionLabel('Tambah Kamera Lainnya')
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('report_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Nama Kapal')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Kondisi Sistem')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Normal' => 'success',
                        'Warning' => 'warning',
                        'Critical' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Normal' => 'Normal',
                        'Warning' => 'Warning',
                        'Critical' => 'Critical',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('Summary PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->url(fn ($record) => '#') 
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCctvReports::route('/'),
            'create' => Pages\CreateCctvReport::route('/create'),
            'edit' => Pages\EditCctvReport::route('/{record}/edit'),
        ];
    }
}