<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentReportResource\Pages;
use App\Models\IncidentReport;
use App\Models\Vessel;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncidentReportResource extends Resource
{
    protected static ?string $model = IncidentReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'ITSM / Insiden';
    protected static ?string $navigationGroup = 'IT Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Group::make()
                    ->schema([
                        // BAGIAN UTAMA (KIRI) - 2 Kolom
                        \Filament\Forms\Components\Section::make('Karakteristik Tiket')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('title')
                                    ->label('Judul Tiket')
                                    ->columnSpanFull()
                                    ->required(),

                                \Filament\Forms\Components\Select::make('category')
                                    ->label('Kategori')
                                    ->options(['Network/Internet' => 'Network/Internet', 'Hardware/CCTV' => 'Hardware/CCTV', 'Software/Aplikasi' => 'Software/Aplikasi', 'Lainnya' => 'Lainnya'])
                                    ->required(),

                                \Filament\Forms\Components\Select::make('asset_id')
                                    ->label('Perangkat / Aset IT')
                                    ->relationship('asset', 'asset_name')
                                    ->searchable()
                                    ->preload()
                                    ->getOptionLabelFromRecordUsing(fn (\App\Models\Asset $record) => "{$record->asset_name} ({$record->asset_type} - {$record->ip_address})"),

                                \Filament\Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Masalah')
                                    ->columnSpanFull()
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]), // Mengambil ruang 2/3 layar (Kiri)

                \Filament\Forms\Components\Group::make()
                    ->schema([
                        // BAGIAN AKTOR & STATUS (KANAN) - 1 Kolom
                        \Filament\Forms\Components\Section::make('Aktor & Status')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('ticket_number')
                                    ->label('No. Tiket')
                                    ->default('INC-' . strtoupper(uniqid()))
                                    ->readOnly(),

                                // Pada bagian form Status:
                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status Penyelesaian')
                            ->options([
                                'Open' => 'Open (Baru)',
                                'In Progress' => 'In Progress (Dikerjakan)',
                                'Resolved' => 'Resolved (Selesai)',
                                'Closed' => 'Closed (Ditutup)',
                            ])
                            ->default('Open')
                            ->live() // Jadikan reaktif
                            ->afterStateUpdated(function ($state) {
                                if (in_array($state, ['Resolved', 'Closed'])) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Wajib Isi Catatan!')
                                        ->body('Tiket diselesaikan. Mohon isi Catatan Penyelesaian (Solusi) di bawah.')
                                        ->warning()
                                        ->send();
                                }
                            })
                            ->required(),

                        // Pada bagian Resolution Note (di section Deskripsi):
                        \Filament\Forms\Components\RichEditor::make('resolution_note')
                            ->label('Catatan Penyelesaian / Solusi')
                            // WAJIB DIISI JIKA STATUSNYA RESOLVED ATAU CLOSED
                            ->required(fn (\Filament\Forms\Get $get) => in_array($get('status'), ['Resolved', 'Closed']))
                            ->visible(fn (\Filament\Forms\Get $get) => in_array($get('status'), ['Resolved', 'Closed'])), // Hanya muncul kalau selesai

                                \Filament\Forms\Components\Select::make('priority')
                                    ->label('Prioritas')
                                    ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High', 'Critical' => 'Critical'])
                                    ->required(),

                                \Filament\Forms\Components\TextInput::make('reported_by')
                                    ->label('Requester (Pelapor)')
                                    ->required(),

                                \Filament\Forms\Components\Select::make('vessel_name')
                                    ->label('Lokasi (Kapal)')
                                    ->options(\App\Models\Vessel::pluck('vessel_name', 'vessel_name'))
                                    ->searchable(),
                            ]),
                    ])->columnSpan(['lg' => 1]), // Mengambil ruang 1/3 layar (Kanan)
            ])->columns(3); // Membagi layar jadi 3 bagian
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')->label('No. Tiket')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('vessel_name')->label('Kapal')->searchable(),

                // Menampilkan nama aset di tabel
                Tables\Columns\TextColumn::make('asset.asset_name')->label('Aset IT')->searchable(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Critical' => 'danger', 'High' => 'warning', 'Medium' => 'info', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger', 'In Progress' => 'warning', 'Resolved' => 'success', 'Closed' => 'gray', default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat Pada')->dateTime('d M Y, H:i')->sortable(),
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
            // Menggunakan backslash (\) di awal agar jalurnya absolut/pasti!
            \App\Filament\Resources\IncidentReportResource\RelationManagers\ThreadsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncidentReports::route('/'),
            'create' => Pages\CreateIncidentReport::route('/create'),
            'edit' => Pages\EditIncidentReport::route('/{record}/edit'),
        ];
    }
}
