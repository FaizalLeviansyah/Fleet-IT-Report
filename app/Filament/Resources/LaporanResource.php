<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan CCTV';

    protected static ?string $navigationGroup = 'CCTV Monitoring';
    protected static ?int $navigationSort = 2; // Agar tampil di bawah Live Monitoring

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Laporan')
                    ->schema([
                        // KITA GUNAKAN KOLOM 'lokasi' (SESUAI TINKER)
                        \Filament\Forms\Components\Select::make('lokasi')
                            ->label('Nama Kapal')
                            ->options(fn () => \App\Models\Vessel::pluck('vessel_name', 'vessel_name')->toArray())
                            ->searchable()
                            ->required(),

                        // KITA GUNAKAN KOLOM 'waktu_kejadian'
                        \Filament\Forms\Components\DateTimePicker::make('waktu_kejadian')
                            ->label('Waktu Laporan')
                            ->displayFormat('d M Y, H:i')
                            ->required(),

                        // KITA GUNAKAN KOLOM 'isi_laporan'
                        \Filament\Forms\Components\Textarea::make('isi_laporan')
                            ->label('Keterangan / Isi Laporan')
                            ->columnSpanFull()
                            ->rows(4)
                            ->nullable(),
                    ])->columns(2),

                \Filament\Forms\Components\Section::make('Status CCTV (Pilih Ceklis/Silang)')
                    ->schema([
                        // STATUS SESUAI TINKER
                        \Filament\Forms\Components\Select::make('status_ccr')
                            ->label('Kamera CCR')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang', 'NA' => 'NA']),

                        \Filament\Forms\Components\Select::make('status_front1')
                            ->label('Kamera Front 1')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang', 'NA' => 'NA']),

                        \Filament\Forms\Components\Select::make('status_front2')
                            ->label('Kamera Front 2')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang', 'NA' => 'NA']),

                        \Filament\Forms\Components\Select::make('status_back1')
                            ->label('Kamera Back 1')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang', 'NA' => 'NA']),

                        \Filament\Forms\Components\Select::make('status_back2')
                            ->label('Kamera Back 2')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang', 'NA' => 'NA']),
                    ])->columns(3),

                // SECTION 3: GALERI GAMBAR (MULTI-UPLOAD)
                \Filament\Forms\Components\Section::make('Galeri Snapshot CCTV')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('gambars')
                            ->relationship('gambars') // Harus sama dengan nama fungsi di Model Laporan
                            ->label('')
                            ->schema([
                                \Filament\Forms\Components\Select::make('channel')
                                    ->label('Channel Kamera')
                                    ->options([
                                        'CCR' => 'CCR',
                                        'AJG' => 'Anjungan (AJG)',
                                        'BRT' => 'Buritan (BRT)',
                                        'ECR' => 'ECR',
                                        'WKN' => 'WKN',
                                        'WKR' => 'WKR',
                                    ])
                                    ->required(),

                                \Filament\Forms\Components\FileUpload::make('path_gambar')
                                    ->label('File Snapshot')
                                    ->image()
                                    ->directory('laporan-images') // Supaya upload baru masuk ke folder yang sama
                                    ->required(),

                                \Filament\Forms\Components\Toggle::make('is_visible')
                                    ->label('Tampilkan di Laporan?')
                                    ->default(true),
                            ])
                            ->columns(3) // Mengatur agar Channel, Upload, dan Toggle bersebelahan
                            ->grid(1)
                            ->defaultItems(0) // Default tidak ada kotak kosong jika data tidak ada
                            ->addActionLabel('Tambah Snapshot Baru'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading() // ANTI LEMOT
            ->columns([
                Tables\Columns\TextColumn::make('lokasi')
                    ->label('Nama Kapal')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('waktu_kejadian')
                    ->label('Waktu Laporan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('isi_laporan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_ccr')
                    ->label('Status CCR')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Ceklis' => 'success',
                        'Silang' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // TAMBAHAN TOMBOL CETAK
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak / PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Laporan $record) => route('cetak.laporan', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            'create' => Pages\CreateLaporan::route('/create'),
            'edit' => Pages\EditLaporan::route('/{record}/edit'),
        ];
    }
}
