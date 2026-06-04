<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Ticket;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationLabel = 'ITSM / Insident';
    protected static ?string $navigationGroup = 'IT MANAGEMENT';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Tiket';
    protected static ?string $pluralModelLabel = 'Daftar Tiket';

    // Helper untuk mapping prioritas ke label & warna
    public static function getPriorityLabel($priorityLevel): string
    {
        return match ((int) $priorityLevel) {
            5 => '🔴 Sangat Tinggi (Kritis)',
            4 => '🟠 Tinggi',
            3 => '🟡 Sedang',
            2 => '🟢 Rendah',
            1 => '🔵 Sangat Rendah',
            default => '⚪ Tidak Diketahui',
        };
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // INJEKSI CSS UNTUK TAMPILAN MAHAL
                Forms\Components\Placeholder::make('css_injector')
                    ->hiddenLabel()
                    ->content(new HtmlString('
                        <style>
                            .ticket-priority-badge input { font-weight: 900 !important; text-align: center; font-size: 1.1em; }
                            .panel-glass { background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(0,0,0,0.05); border-radius: 12px; }
                            .dark .panel-glass { background: rgba(15, 23, 42, 0.5); border-color: rgba(255,255,255,0.05); }
                        </style>
                    '))
                    ->columnSpanFull(), // 🚨 KUNCI PERBAIKAN: Agar CSS tidak memakan jatah slot barisan 🚨

                // --- GROUP KIRI (LEBAR 2 KOLOM) ---
                Forms\Components\Group::make()->schema([

                    Forms\Components\Section::make('Informasi Tiket')
                        ->icon('heroicon-o-document-text')
                        ->extraAttributes(['class' => 'panel-glass'])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Judul Tiket')
                                ->placeholder('Contoh: Printer lantai 2 tidak bisa menarik kertas')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Forms\Components\Select::make('type')
                                ->label('Tipe Tiket')
                                ->options([
                                    Ticket::TYPE_INCIDENT => '⚠️ Insiden (Gangguan / Rusak)',
                                    Ticket::TYPE_REQUEST  => '💡 Request (Permintaan Layanan)',
                                ])
                                ->default(Ticket::TYPE_INCIDENT)
                                ->required(),

                            Forms\Components\Select::make('status')
                                ->label('Status Tiket')
                                ->options([
                                    Ticket::STATUS_NEW      => '🆕 New (Baru)',
                                    Ticket::STATUS_ASSIGNED => '👨‍💻 Processing (Assigned)',
                                    Ticket::STATUS_PLANNED  => '📅 Processing (Planned)',
                                    Ticket::STATUS_PENDING  => '⏳ Pending (Menunggu User)',
                                    Ticket::STATUS_SOLVED   => '✅ Solved (Selesai)',
                                    Ticket::STATUS_CLOSED   => '🔒 Closed (Ditutup)',
                                ])
                                ->default(Ticket::STATUS_NEW)
                                ->required(),

                            Forms\Components\RichEditor::make('content')
                                ->label('Deskripsi Detail')
                                ->placeholder('Jelaskan sedetail mungkin masalah atau permintaan Anda...')
                                ->required()
                                ->fileAttachmentsDirectory('tickets')
                                ->columnSpanFull(),
                        ])->columns(2),

                    Forms\Components\Section::make('Informasi Perangkat & Lokasi (Auto-Detect)')
                        ->icon('heroicon-o-cpu-chip')
                        ->description('Dilacak secara otomatis dari Agent di perangkat Anda.')
                        ->extraAttributes(['class' => 'panel-glass'])
                        ->schema([
                            Forms\Components\TextInput::make('company_name')
                                ->label('Perusahaan (Entity)')
                                ->default(fn () => auth()->user()->company ?? 'PT CTP')
                                ->disabled()
                                ->dehydrated(true)
                                ->prefixIcon('heroicon-m-building-office-2'),

                            Forms\Components\TextInput::make('location_name')
                                ->label('Lokasi Departemen')
                                ->default(fn () => auth()->user()->location ?? 'Lantai 4 - Divisi Finance')
                                ->disabled()
                                ->dehydrated(true)
                                ->prefixIcon('heroicon-m-map-pin'),

                            Forms\Components\TextInput::make('asset_hostname')
                                ->label('Hostname (Asset)')
                                ->default(fn () => auth()->user()->hostname ?? 'DESKTOP-FIN-04')
                                ->disabled()
                                ->dehydrated(true)
                                ->prefixIcon('heroicon-m-computer-desktop'),
                        ])->columns(3),

                ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()->schema([

                    Forms\Components\Section::make('Aktor (Pelaku)')
                        ->icon('heroicon-o-users')
                        ->extraAttributes(['class' => 'panel-glass', 'style' => 'position: relative; z-index: 30;'])
                        ->schema([
                            Forms\Components\Hidden::make('requester_id')
                                ->default(fn () => auth()->id() ?? 1),

                            Forms\Components\TextInput::make('requester_name')
                                ->label('Pemohon (Requester)')
                                ->default(fn () => auth()->user() ? auth()->user()->full_name : 'System')
                                ->formatStateUsing(fn ($record) => $record ? $record->requester->full_name : (auth()->user()->full_name ?? 'System'))
                                ->disabled()
                                ->dehydrated(false)
                                ->prefixIcon('heroicon-m-user-circle'),

                            Forms\Components\Select::make('assigned_to_id')
                                ->label('Ditugaskan (Teknisi)')
                                ->options(User::whereIn('full_name', [
                                    'FAIZAL LEVIANSYAH',
                                    'FARHAN ARIF INDIARTO',
                                    'HENDRI SETIO PRAKOSO'
                                ])->pluck('full_name', 'employee_id'))
                                ->searchable()
                                ->prefixIcon('heroicon-m-wrench-screwdriver'),

                            Forms\Components\Select::make('observer_id')
                                ->label('Pengamat (CC)')
                                ->options(User::pluck('full_name', 'employee_id'))
                                ->searchable()
                                ->prefixIcon('heroicon-m-eye'),
                        ])->columns(1),

                    Forms\Components\Section::make('Matriks Prioritas')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->description('Dihitung otomatis.')
                        ->extraAttributes(['class' => 'panel-glass'])
                        ->schema([
                            Forms\Components\Select::make('urgency')
                                ->label('Urgency (Desakan)')
                                ->options([
                                    5 => 'Sangat Tinggi', 4 => 'Tinggi', 3 => 'Sedang', 2 => 'Rendah', 1 => 'Sangat Rendah'
                                ])
                                ->default(3)
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => $set('priority_display', self::getPriorityLabel(Ticket::computePriority($get('urgency'), $get('impact'))))),

                            Forms\Components\Select::make('impact')
                                ->label('Impact (Dampak)')
                                ->options([
                                    5 => 'Sangat Tinggi', 4 => 'Tinggi', 3 => 'Sedang', 2 => 'Rendah', 1 => 'Sangat Rendah'
                                ])
                                ->default(3)
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => $set('priority_display', self::getPriorityLabel(Ticket::computePriority($get('urgency'), $get('impact'))))),

                            Forms\Components\TextInput::make('priority_display')
                                ->label('HASIL PRIORITAS')
                                ->disabled()
                                ->dehydrated(false)
                                ->extraInputAttributes(['class' => 'ticket-priority-badge text-white bg-slate-800 dark:bg-slate-900 rounded-lg py-2'])
                                ->formatStateUsing(fn (Get $get, $record) => $record ? self::getPriorityLabel($record->priority) : self::getPriorityLabel(Ticket::computePriority($get('urgency'), $get('impact')))),
                        ])->columns(1),

                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Judul Tiket')
                    ->searchable()
                    ->limit(40)
                    ->description(fn (Ticket $record): string => strip_tags(substr($record->content, 0, 50)) . '...'),

                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->icon('heroicon-m-user')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        Ticket::STATUS_NEW      => 'New',
                        Ticket::STATUS_ASSIGNED => 'Assigned',
                        Ticket::STATUS_PLANNED  => 'Planned',
                        Ticket::STATUS_PENDING  => 'Pending',
                        Ticket::STATUS_SOLVED   => 'Solved',
                        Ticket::STATUS_CLOSED   => 'Closed',
                        default                 => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        Ticket::STATUS_NEW      => 'danger',
                        Ticket::STATUS_ASSIGNED => 'warning',
                        Ticket::STATUS_PLANNED  => 'info',
                        Ticket::STATUS_PENDING  => 'gray',
                        Ticket::STATUS_SOLVED   => 'success',
                        Ticket::STATUS_CLOSED   => 'success',
                        default                 => 'gray',
                    }),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->formatStateUsing(fn ($state) => self::getPriorityLabel($state))
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        5 => 'danger',
                        4 => 'warning',
                        3 => 'info',
                        2 => 'success',
                        1 => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                // Nanti kita bisa tambah filter berdasarkan Status / Prioritas di sini
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            \App\Filament\Resources\TicketResource\RelationManagers\FollowupsRelationManager::class,
            \App\Filament\Resources\TicketResource\RelationManagers\TasksRelationManager::class,
            \App\Filament\Resources\TicketResource\RelationManagers\SolutionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
