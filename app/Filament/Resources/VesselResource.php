<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VesselResource\Pages;
use App\Models\Vessel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\KeyValue;

class VesselResource extends Resource
{
    protected static ?string $model = Vessel::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Vessel Management';
    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Kapal')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Nama Perusahaan (Company)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('vessel_name')
                            ->label('Nama Kapal')
                            ->required()
                            ->maxLength(255),

                        \Filament\Forms\Components\Select::make('pic_name')
                            ->label('Nama PIC Kapal')
                            ->options(
                                \App\Models\Employee::where('is_active', 1)->pluck('full_name', 'full_name')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan / Spesifikasi Kapal')
                            ->columnSpanFull()
                            ->rows(3),

                        // 💡 FIX: Menyamakan default dengan Live Monitoring
                        KeyValue::make('cctv_names')
                            ->label('Konfigurasi Channel CCTV (Universal)')
                            ->keyLabel('Kode CH Asli (AJG, BRT, dll)')
                            ->valueLabel('Nama Label (Tampil di PDF/Monitoring)')
                            ->addActionLabel('Tambah Kamera Baru')
                            ->reorderable()
                            ->default([
                                'AJG' => 'CCTV 1 (Cam A)',
                                'BRT' => 'CCTV 2 (Cam B)',
                                'CCR' => 'CCTV 3 (Cam C)',
                                'ECR' => 'CCTV 4 (Cam D)',
                                'WKN' => 'CCTV 5 (Cam E)',
                                'WKR' => 'CCTV 6 (Cam F)',
                            ])
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Nama Kapal')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pic_name')
                    ->label('PIC')
                    ->searchable(),

                // 💡 UX UPGRADE: Menambahkan Kolom Total Kamera
                Tables\Columns\TextColumn::make('cctv_names')
                    ->label('Total Kamera')
                    ->getStateUsing(fn (Vessel $record): string => is_array($record->cctv_names) ? count($record->cctv_names) . ' Kamera' : '6 Kamera (Default)')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Didaftarkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth('4xl'),
                Tables\Actions\EditAction::make()->modalWidth('4xl'),
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
            'index' => Pages\ListVessels::route('/'),
        ];
    }
}
