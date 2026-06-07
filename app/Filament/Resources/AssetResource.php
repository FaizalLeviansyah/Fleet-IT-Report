<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $navigationLabel = 'Manajemen Aset (ITAM)';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Tabs::make('Asset Details')
                    ->tabs([
                        // TAB 1: INFORMASI UTAMA & HARDWARE
                        \Filament\Forms\Components\Tabs\Tab::make('Info & Spesifikasi')
                            ->icon('heroicon-m-information-circle')
                            ->schema([
                                \Filament\Forms\Components\Grid::make(2)->schema([
                                    \Filament\Forms\Components\TextInput::make('asset_name')
                                    ->label('Nama Aset (Hostname)')
                                    ->required(),
                                    \Filament\Forms\Components\Select::make('asset_type')->label('Tipe Aset')
                                        ->options(['PC/Laptop' => 'PC/Laptop', 'Printer' => 'Printer', 'Network/Router' => 'Network/Router', 'Lainnya' => 'Lainnya']),
                                    \Filament\Forms\Components\Select::make('vessel_id')->label('Lokasi Kapal')
                                        ->options(\App\Models\Vessel::pluck('vessel_name', 'id'))->searchable(),
                                    \Filament\Forms\Components\Select::make('status')->label('Status')
                                        ->options(['Active' => 'Active', 'Inactive' => 'Inactive', 'Maintenance' => 'Maintenance', 'Broken' => 'Broken']),

                                    // Hardware Specs
                                    \Filament\Forms\Components\TextInput::make('cpu_model')->label('Prosesor (CPU)'),
                                    \Filament\Forms\Components\TextInput::make('total_ram')->label('Kapasitas RAM'),
                                    \Filament\Forms\Components\TextInput::make('disk_space')->label('Sisa Penyimpanan'),
                                    \Filament\Forms\Components\TextInput::make('os_version')->label('Sistem Operasi')->columnSpanFull(),
                                ]),
                            ]),

                        // TAB 2: JARINGAN & IDENTITAS
                        \Filament\Forms\Components\Tabs\Tab::make('Jaringan & Identitas')
                            ->icon('heroicon-m-wifi')
                            ->schema([
                                \Filament\Forms\Components\Grid::make(2)->schema([
                                    \Filament\Forms\Components\TextInput::make('ip_address')->label('IP Address'),
                                    \Filament\Forms\Components\TextInput::make('mac_address')->label('MAC Address'),
                                    \Filament\Forms\Components\TextInput::make('hardware_uuid')->label('Hardware UUID'),
                                    \Filament\Forms\Components\TextInput::make('serial_number')->label('Serial Number'),
                                    \Filament\Forms\Components\TextInput::make('current_user')->label('Pengguna Terakhir (User)'),
                                    \Filament\Forms\Components\DateTimePicker::make('last_boot_time')->label('Terakhir Dinyalakan (Boot)'),
                                    \Filament\Forms\Components\DateTimePicker::make('last_seen')->label('Terakhir Terdeteksi (Agent)'),
                                    \Filament\Forms\Components\TextInput::make('ip_address')
                                        ->label('IP Address')
                                        ->ipv4(), // Cerdas: Menolak input jika bukan format IP yang benar

                                    \Filament\Forms\Components\TextInput::make('mac_address')
                                        ->label('MAC Address')
                                        ->macAddress(), // Cerdas: Menolak input jika bukan format MAC
                                ]),
                            ]),

                        // TAB 3: DAFTAR SOFTWARE (Otomatis dibaca dari Array/JSON)
                        \Filament\Forms\Components\Tabs\Tab::make('Daftar Software')
                            ->icon('heroicon-m-window')
                            ->schema([
                                \Filament\Forms\Components\Repeater::make('software_list')
                                    ->label('Perangkat Lunak Terinstal')
                                    ->schema([
                                        \Filament\Forms\Components\TextInput::make('name')->label('Nama Software')->required(),
                                        \Filament\Forms\Components\TextInput::make('version')->label('Versi'),
                                        \Filament\Forms\Components\TextInput::make('publisher')->label('Penerbit'),
                                    ])
                                    ->columns(3)
                                    ->defaultItems(0)
                                    ->reorderable(false)
                                    // Kita buat readonly karena data ini diinjeksi oleh agent otomatis
                                    ->disabled(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('asset_name')
                ->label('Nama Komputer')
                ->searchable()
                ->weight('bold'),

            Tables\Columns\TextColumn::make('current_user')
                ->label('User Terakhir')
                ->searchable()
                ->icon('heroicon-m-user'),

            Tables\Columns\TextColumn::make('ip_address')
                ->label('IP Address')
                ->copyable(),

            Tables\Columns\TextColumn::make('model')
                ->label('Merek / Model')
                ->description(fn ($record) => $record->manufacturer)
                ->limit(20),

            Tables\Columns\TextColumn::make('total_ram')
                ->label('Kapasitas RAM')
                ->badge()
                ->color('info'),

            Tables\Columns\TextColumn::make('last_seen')
                ->label('Terakhir Online')
                ->since() // Otomatis menjadi "2 minutes ago"
                ->badge()
                ->color('success'),

            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Active' => 'success',
                    default => 'gray',
                }),
        ])


        ->filters([
            // ... biarkan filter yang sudah ada ...
        ])
        ->actions([
                Tables\Actions\ViewAction::make(), // 👈 Tambahkan ini agar bisa melihat detail
                Tables\Actions\EditAction::make(),
            ]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            // 'create' => Pages\CreateAsset::route('/create'),
            // 'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
