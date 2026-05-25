<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentReportResource\Pages;
use App\Models\IncidentReport;
use App\Models\Vessel;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IncidentReportResource extends Resource
{
    protected static ?string $model = IncidentReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    // 1. Kolom apa yang mau dijadikan Judul saat dicari?
    protected static ?string $recordTitleAttribute = 'ticket_number';

    // 2. Kolom apa saja yang bisa dicari sistem saat Anda mengetik di Ctrl+K?
    public static function getGloballySearchableAttributes(): array
    {
        return ['ticket_number', 'vessel_name', 'category', 'status'];
    }

    protected static ?string $navigationLabel = 'ITSM / Insident';
    protected static ?string $navigationGroup = 'IT Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Group::make()
                    ->schema([
                        // 👇 SECTION BARU: LOKASI DINAMIS (ANTI DOUBLE-INPUT) 👇
                        \Filament\Forms\Components\Section::make('Pemilihan Lokasi Insiden')
                            ->description('Tentukan letak kendala secara spesifik (Armada atau Gedung).')
                            ->schema([
                                \Filament\Forms\Components\Radio::make('location_type')
                                    ->label('Jenis Lokasi')
                                    ->options([
                                        'Kapal' => '🚢 Armada Kapal',
                                        'Kantor' => '🏢 Gedung Kantor',
                                    ])
                                    ->inline()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        // RESET DATA JIKA PILIHAN BERUBAH
                                        $set('vessel_name', null);
                                        $set('office_name', null);
                                        $set('floor_level', null);
                                    })
                                    ->required()
                                    ->columnSpanFull(),

                                // MUNCUL JIKA PILIH KAPAL
                                \Filament\Forms\Components\Select::make('vessel_name')
                                    ->label('Pilih Armada Kapal')
                                    ->options(\App\Models\Vessel::pluck('vessel_name', 'vessel_name'))
                                    ->searchable()
                                    ->visible(fn (Get $get) => $get('location_type') === 'Kapal')
                                    ->required(fn (Get $get) => $get('location_type') === 'Kapal'),

                                // MUNCUL JIKA PILIH KANTOR
                                \Filament\Forms\Components\Select::make('office_name')
                                    ->label('Pilih Gedung Kantor')
                                    ->options([
                                        'ASM' => 'Kantor Pusat (ASM)',
                                        'CTP' => 'Kantor CTP',
                                        'ACS' => 'Kantor ACS',
                                    ])
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('floor_level', null)) // Reset lantai
                                    ->visible(fn (Get $get) => $get('location_type') === 'Kantor')
                                    ->required(fn (Get $get) => $get('location_type') === 'Kantor'),

                                // PILIHAN LANTAI PINTAR (ASM = 1 Lantai, CTP/ACS = 4 Lantai)
                                \Filament\Forms\Components\Select::make('floor_level')
                                    ->label('Detail Lantai Ke-')
                                    ->options(function (Get $get) {
                                        $office = $get('office_name');
                                        if ($office === 'ASM') {
                                            return ['Lantai 1' => 'Lantai 1'];
                                        }
                                        if (in_array($office, ['CTP', 'ACS'])) {
                                            return [
                                                'Lantai 1' => 'Lantai 1',
                                                'Lantai 2' => 'Lantai 2',
                                                'Lantai 3' => 'Lantai 3',
                                                'Lantai 4' => 'Lantai 4',
                                            ];
                                        }
                                        return [];
                                    })
                                    ->visible(fn (Get $get) => filled($get('office_name')) && $get('location_type') === 'Kantor')
                                    ->required(fn (Get $get) => $get('location_type') === 'Kantor'),
                            ])->columns(2),

                        // BAGIAN UTAMA
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
                    ])->columnSpan(['lg' => 2]),

                \Filament\Forms\Components\Group::make()
                    ->schema([
                        // BAGIAN AKTOR & STATUS
                        \Filament\Forms\Components\Section::make('Aktor & Status')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('ticket_number')
                                    ->label('No. Tiket')
                                    ->default('INC-' . strtoupper(uniqid()))
                                    ->readOnly(),

                                \Filament\Forms\Components\Select::make('status')
                                    ->label('Status Penyelesaian')
                                    ->options([
                                        'Open' => 'Open (Baru)',
                                        'In Progress' => 'In Progress (Dikerjakan)',
                                        'Resolved' => 'Resolved (Selesai)',
                                        'Closed' => 'Closed (Ditutup)',
                                    ])
                                    ->default('Open')
                                    ->live()
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

                                \Filament\Forms\Components\RichEditor::make('resolution_note')
                                    ->label('Catatan Penyelesaian / Solusi')
                                    ->required(fn (\Filament\Forms\Get $get) => in_array($get('status'), ['Resolved', 'Closed']))
                                    ->visible(fn (\Filament\Forms\Get $get) => in_array($get('status'), ['Resolved', 'Closed'])),

                                \Filament\Forms\Components\Select::make('priority')
                                    ->label('Prioritas')
                                    ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High', 'Critical' => 'Critical'])
                                    ->required(),

                                \Filament\Forms\Components\TextInput::make('reported_by')
                                    ->label('Requester (Pelapor)')
                                    ->required(),

                                // vessel_name lama di kanan SUDAH DIHAPUS agar tidak double
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')->label('No. Tiket')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->limit(30),

                // Menampilkan lokasi (Bisa Kapal atau Kantor)
                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Lokasi')
                    ->getStateUsing(function (IncidentReport $record) {
                        if ($record->location_type === 'Kantor') {
                            return $record->office_name . ' (' . $record->floor_level . ')';
                        }
                        return $record->vessel_name;
                    })
                    ->searchable(),

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
                Tables\Actions\EditAction::make()->slideOver()->modalWidth('4xl'),
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
            \App\Filament\Resources\IncidentReportResource\RelationManagers\ThreadsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncidentReports::route('/'),
            // 'create' => Pages\CreateIncidentReport::route('/create'),
            // 'edit' => Pages\EditIncidentReport::route('/{record}/edit'),
        ];
    }
}
