<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonalItReportResource\Pages;
use App\Models\PersonalItReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Carbon\Carbon;

class PersonalItReportResource extends Resource
{
    protected static ?string $model = PersonalItReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Kinerja IT';
    protected static ?string $navigationGroup = 'HR / Pekerjaan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Periode Laporan & Status')
                    ->description('Secara default, waktu terkunci pada Senin-Jumat minggu ini.')
                    ->schema([
                        // ID Pegawai (Sementara diisi default 1 sebelum SSO ada)
                        \Filament\Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id() ?? 1),

                        \Filament\Forms\Components\Select::make('status')
                            ->label('Status Laporan')
                            ->options([
                                'Draft' => 'Draft (Dicicil / Belum Selesai)',
                                'Submitted' => 'Submitted (Selesai & Kirim)',
                            ])
                            ->default('Draft')
                            ->required(),

                        // TOGGLE MERAH: Saklar Laporan Terlambat
                        \Filament\Forms\Components\Toggle::make('is_late')
                            ->label('Saya membuat laporan untuk minggu lalu / terlambat')
                            ->onColor('danger')
                            ->live() // KUNCI SIHIR: Membuat form bereaksi seketika saat diklik!
                            ->dehydrated(false), // Tidak disimpan ke database, murni untuk trigger logika

                        \Filament\Forms\Components\Grid::make(2)->schema([
                            \Filament\Forms\Components\DatePicker::make('start_date')
                                ->label('Tanggal Mulai (Wajib Senin)')
                                ->default(Carbon::now()->startOfWeek())
                                // Kunci tanggal jika toggle merah tidak dinyalakan
                                ->readOnly(fn (Get $get) => !$get('is_late'))
                                ->live() // Wajib ada agar bereaksi real-time
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state) {
                                        $date = Carbon::parse($state);
                                        // LOGIKA PINTAR: Cek apakah hari yang dipilih BUKAN hari Senin
                                        if (!$date->isMonday()) {
                                            // Tampilkan Pop-up Peringatan (SweetAlert Filament)
                                            \Filament\Notifications\Notification::make()
                                                ->title('Tanggal Tidak Valid!')
                                                ->body('Laporan mingguan harus selalu dimulai pada hari Senin.')
                                                ->danger() // Warna merah
                                                ->send();

                                            // Kosongkan formnya kembali agar user tidak bisa lanjut
                                            $set('start_date', null);
                                            $set('end_date', null);
                                        } else {
                                            // Jika benar Senin, OTOMATIS isikan hari Jumat (+4 hari) di end_date
                                            $set('end_date', $date->copy()->addDays(4)->format('Y-m-d'));
                                        }
                                    }
                                })
                                ->required(),

                            \Filament\Forms\Components\DatePicker::make('end_date')
                                ->label('Tanggal Selesai (Otomatis Jumat)')
                                ->default(Carbon::now()->startOfWeek()->addDays(4))
                                // end_date selalu dikunci (readOnly) karena dihitung otomatis dari start_date!
                                ->readOnly()
                                ->required(),
                        ]),

                        // Keterangan terlambat HANYA MUNCUL DAN WAJIB JIKA toggle dinyalakan
                        \Filament\Forms\Components\Textarea::make('late_remark')
                            ->label('Alasan Keterlambatan')
                            ->visible(fn (Get $get) => $get('is_late'))
                            ->required(fn (Get $get) => $get('is_late'))
                            ->columnSpanFull(),
                    ])->columns(2),

                // SECTION 2: REPEATER UNTUK RENCANA KERJA
                \Filament\Forms\Components\Section::make('Daftar Rencana Pekerjaan (Planned Tasks)')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('plannedTasks')
                            ->relationship('plannedTasks')
                            ->label('')
                            ->schema([
                                \Filament\Forms\Components\TextInput::make('plan_name')
                                    ->label('Nama Rencana / Tugas')
                                    ->required(),

                                \Filament\Forms\Components\TextInput::make('target')
                                    ->label('Target Penyelesaian'),

                                \Filament\Forms\Components\Select::make('priority')
                                    ->label('Prioritas')
                                    ->options([
                                        'Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High'
                                    ])->default('Medium')->required(),

                                \Filament\Forms\Components\DatePicker::make('deadline')
                                    ->label('Deadline Target'),

                                \Filament\Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Tambahan')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['plan_name'] ?? null) // Otomatis jadikan judul kalau di-collapse
                            ->collapsible() // Bisa dilipat agar rapi
                            ->addActionLabel('Tambah Rencana Kerja'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')->label('Periode Mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('end_date')->label('Periode Selesai')->date('d M Y'),
                Tables\Columns\TextColumn::make('planned_tasks_count')
                    ->counts('plannedTasks')
                    ->label('Jml Tugas')
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'warning',
                        'Submitted' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->modalWidth('4xl'),
                Tables\Actions\EditAction::make()->modalWidth('4xl'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonalItReports::route('/'),
            // 'create' => Pages\CreatePersonalItReport::route('/create'),
            // 'edit' => Pages\EditPersonalItReport::route('/{record}/edit'),
        ];
    }
}
