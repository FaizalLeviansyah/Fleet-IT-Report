<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Filament\Resources\LaporanResource\RelationManagers;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: Informasi Utama
                \Filament\Forms\Components\Section::make('Informasi Laporan')
                    ->schema([
                        // KEAJAIBAN INTEGRASI ADA DI SINI!
                        // Mengubah input text biasa menjadi Select Dropdown yang mengambil data dari tabel Vessels
                        \Filament\Forms\Components\Select::make('lokasi')
                            ->label('Nama Kapal')
                            ->options(
                                \App\Models\Vessel::pluck('vessel_name', 'vessel_name')
                            )
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\DateTimePicker::make('waktu_kejadian')
                            ->label('Waktu Laporan')
                            ->displayFormat('d M Y, H:i')
                            ->required(),

                        \Filament\Forms\Components\Textarea::make('isi_laporan')
                            ->label('Keterangan / Isi Laporan')
                            ->columnSpanFull()
                            ->rows(4)
                            ->required(),
                    ])->columns(2),

                // SECTION 2: Status CCTV
                \Filament\Forms\Components\Section::make('Status CCTV (Pilih Ceklis/Silang)')
                    ->schema([
                        \Filament\Forms\Components\Select::make('status_ccr')
                            ->label('Kamera CCR')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),

                        \Filament\Forms\Components\Select::make('status_ajg')
                            ->label('Kamera Anjungan')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),

                        \Filament\Forms\Components\Select::make('status_brt')
                            ->label('Kamera Buritan')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),

                        \Filament\Forms\Components\Select::make('status_ecr')
                            ->label('Kamera ECR')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),

                        \Filament\Forms\Components\Select::make('status_wkn')
                            ->label('Kamera WKN')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),

                        \Filament\Forms\Components\Select::make('status_wkr')
                            ->label('Kamera WKR')
                            ->options(['Ceklis' => 'Ceklis', 'Silang' => 'Silang'])
                            ->default('Ceklis'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_ccr')
                    ->label('Status CCR')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
