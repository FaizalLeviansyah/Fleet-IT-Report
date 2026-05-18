<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPegawaiResource\Pages;
use App\Models\LaporanPegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LaporanPegawaiResource extends Resource
{
    protected static ?string $model = LaporanPegawai::class;
    // Ikon orang berkelompok
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Laporan Pegawai';
    protected static ?string $navigationGroup = 'HR / Pekerjaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Detail Pekerjaan Pegawai')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('judul_laporan')
                            ->label('Judul Laporan / Tugas')
                            ->required(),

                        \Filament\Forms\Components\TextInput::make('nama_pegawai')
                            ->label('Nama Pegawai')
                            ->required(),

                        \Filament\Forms\Components\DateTimePicker::make('waktu_mulai')
                            ->label('Waktu Mulai')
                            ->required(),

                        \Filament\Forms\Components\DateTimePicker::make('waktu_selesai')
                            ->label('Waktu Selesai')
                            ->required(),

                        \Filament\Forms\Components\Textarea::make('deskripsi_pekerjaan')
                            ->label('Deskripsi / Hasil Pekerjaan')
                            ->columnSpanFull()
                            ->rows(4)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pegawai')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('judul_laporan')
                    ->label('Tugas / Pekerjaan')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->defaultSort('waktu_mulai', 'desc') // Otomatis mengurutkan dari yang terbaru
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanPegawais::route('/'),
            'create' => Pages\CreateLaporanPegawai::route('/create'),
            'edit' => Pages\EditLaporanPegawai::route('/{record}/edit'),
        ];
    }
}
