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
use Filament\Forms\Set;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class PersonalItReportResource extends Resource
{
    protected static ?string $model = PersonalItReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Kinerja IT';
    protected static ?string $navigationGroup = 'HR / Pekerjaan';
    protected static ?int $navigationSort = 1;

    // 💡 SECURITY (RBAC): Sembunyikan menu ini dari Client (Owner)
    public static function canViewAny(): bool
    {
        return strtolower(auth()->user()->role) !== 'owner';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('ux_and_logic')
                    ->hiddenLabel()
                    ->content(new HtmlString(<<<'HTML'
                        <style>
                            .glass-repeater .fi-rep-item {
                                background: rgba(239, 246, 255, 0.4) !important;
                                backdrop-filter: blur(12px) !important;
                                border: 1px solid rgba(37, 99, 235, 0.1) !important;
                                border-left: 6px solid #2563EB !important;
                                border-radius: 12px !important;
                                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
                                transition: all 0.3s ease;
                            }
                            .glass-repeater .fi-rep-item-header {
                                background: rgba(37, 99, 235, 0.12) !important;
                                border-bottom: 1px solid rgba(37, 99, 235, 0.1) !important;
                                padding: 1.2rem 1.5rem !important;
                            }
                            .dark .glass-repeater .fi-rep-item {
                                background: rgba(15, 23, 42, 0.6) !important;
                                border-left-color: #38BDF8 !important;
                            }
                            .dark .glass-repeater .fi-rep-item-header {
                                background: rgba(56, 189, 248, 0.1) !important;
                            }
                        </style>

                        <div x-data
                             x-on:swal-confirm-late-turnoff.window="
                                let evtData = $event.detail;
                                if (Array.isArray(evtData)) evtData = evtData[0];
                                if (!evtData || !evtData.statePath) return;

                                let statePath = evtData.statePath;
                                let livewireId = evtData.livewireId;
                                let basePath = statePath.substring(0, statePath.lastIndexOf('.'));

                                Swal.fire({
                                    title: 'Kembalikan ke Minggu Ini?',
                                    text: 'Reset tanggal ke minggu ini atau biarkan tetap di minggu lalu?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ya, Reset',
                                    cancelButtonText: 'Tetap Minggu Lalu'
                                }).then((result) => {
                                    let component = window.Livewire.find(livewireId);
                                    if (result.isConfirmed) {
                                        component.$set(basePath + '.force_reset_trigger', Date.now());
                                    } else {
                                        component.$set(statePath, true);
                                    }
                                });
                             "
                        ></div>
                    HTML)),

                Forms\Components\Hidden::make('force_reset_trigger')
                    ->dehydrated(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        $set('is_late', false);
                        $newStart = Carbon::now()->startOfWeek();
                        $set('start_date', $newStart->format('Y-m-d'));
                        $set('end_date', $newStart->copy()->addDays(4)->format('Y-m-d'));

                        $actualTasks = $get('actualTasks') ?? [];
                        $daysMap = ['Senin'=>0, 'Selasa'=>1, 'Rabu'=>2, 'Kamis'=>3, 'Jumat'=>4];
                        foreach($actualTasks as $k => $t) {
                            if(isset($daysMap[$t['day']])) $actualTasks[$k]['task_date'] = $newStart->copy()->addDays($daysMap[$t['day']])->format('Y-m-d');
                        }
                        $set('actualTasks', $actualTasks);

                        $plannedTasks = $get('plannedTasks') ?? [];
                        $planDaysMap = ['Senin'=>7, 'Selasa'=>8, 'Rabu'=>9, 'Kamis'=>10, 'Jumat'=>11];
                        foreach($plannedTasks as $k => $t) {
                            if(isset($planDaysMap[$t['day']])) $plannedTasks[$k]['deadline'] = $newStart->copy()->addDays($planDaysMap[$t['day']])->format('Y-m-d');
                        }
                        $set('plannedTasks', $plannedTasks);

                        Notification::make()->title('Jadwal Direset ke Minggu Ini')->success()->send();
                    }),

                Forms\Components\Section::make('Periode Laporan & Status')
                    ->description('Waktu terkunci pada Senin-Jumat minggu ini. Gunakan toggle merah jika terlambat.')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')->default(auth()->id() ?? 1),

                        Forms\Components\Select::make('pic_name')
                            ->label('PIC Laporan')
                            ->options(['Levi' => 'Levi', 'Farhan' => 'Farhan'])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Laporan')
                            ->options([
                                'Draft' => '📝 Draft (Bisa dicicil & diedit nanti)',
                                'Submitted' => '✅ Submitted (Selesai & Kirim)',
                            ])
                            ->default('Draft')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function () {
                                Notification::make()->title('Disimpan sebagai Draft')->body('Silakan cicil pekerjaan Anda')->success()->send();
                            }),

                        Forms\Components\Toggle::make('is_late')
                            ->label('Buka Kunci (Laporan Minggu Lalu / Terlambat)')
                            ->onColor('danger')
                            ->live()
                            ->dehydrated(false)
                            ->afterStateUpdated(function ($state, Get $get, \Livewire\Component $livewire, Forms\Components\Toggle $component) {
                                if (!$state) {
                                    $startDate = Carbon::parse($get('start_date'));
                                    if ($startDate->isBefore(Carbon::now()->startOfWeek())) {
                                        $livewire->dispatch('swal-confirm-late-turnoff', statePath: $component->getStatePath(), livewireId: $livewire->getId());
                                    }
                                } else {
                                    Notification::make()->title('Kunci Terbuka 🔓')->warning()->send();
                                }
                            }),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DatePicker::make('start_date')
                                ->label('Tanggal Mulai')
                                ->helperText('WAJIB MEMILIH HARI SENIN.')
                                ->default(Carbon::now()->startOfWeek())
                                ->readOnly(fn (Get $get) => !$get('is_late'))
                                ->live()
                                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                    if ($state) {
                                        $date = Carbon::parse($state);
                                        if (!$date->isMonday()) {
                                            Notification::make()->title('Tanggal Ditolak!')->body('HANYA BISA memilih hari Senin.')->danger()->send();
                                            $set('start_date', null);
                                            $set('end_date', null);
                                        } else {
                                            $set('end_date', $date->copy()->addDays(4)->format('Y-m-d'));

                                            $actualTasks = $get('actualTasks') ?? [];
                                            $daysMap = ['Senin'=>0, 'Selasa'=>1, 'Rabu'=>2, 'Kamis'=>3, 'Jumat'=>4];
                                            foreach($actualTasks as $k => $t) {
                                                if(isset($daysMap[$t['day']])) $actualTasks[$k]['task_date'] = $date->copy()->addDays($daysMap[$t['day']])->format('Y-m-d');
                                            }
                                            $set('actualTasks', $actualTasks);

                                            $plannedTasks = $get('plannedTasks') ?? [];
                                            $planDaysMap = ['Senin'=>7, 'Selasa'=>8, 'Rabu'=>9, 'Kamis'=>10, 'Jumat'=>11];
                                            foreach($plannedTasks as $k => $t) {
                                                if(isset($planDaysMap[$t['day']])) $plannedTasks[$k]['deadline'] = $date->copy()->addDays($planDaysMap[$t['day']])->format('Y-m-d');
                                            }
                                            $set('plannedTasks', $plannedTasks);

                                            Notification::make()->title('Periode Valid')->success()->send();
                                        }
                                    }
                                })->required(),

                            Forms\Components\DatePicker::make('end_date')
                                ->label('Tanggal Selesai')
                                ->helperText('Otomatis Hari Jumat.')
                                ->default(Carbon::now()->startOfWeek()->addDays(4))
                                ->readOnly()->required(),
                        ]),
                    ])->columns(2),

                Forms\Components\Section::make('Pekerjaan Yang Dilakukan (Actual Tasks)')
                    ->description('Bisa dikosongkan (Draft) untuk dicicil besok hari.')
                    ->schema([
                        Forms\Components\Repeater::make('actualTasks')
                            ->relationship('actualTasks')
                            ->extraAttributes(['class' => 'glass-repeater'])
                            ->label('')
                            ->schema([
                                Forms\Components\Hidden::make('day'),
                                Forms\Components\Hidden::make('task_date'),

                                Forms\Components\Repeater::make('tasks')
                                    ->label('Daftar Tugas')
                                    ->schema([
                                        Forms\Components\Grid::make(12)->schema([
                                            Forms\Components\Textarea::make('task_description')
                                                ->label('Pekerjaan Yang Dilakukan')
                                                ->placeholder('Contoh: Maintenance jaringan...')
                                                ->rows(3)
                                                ->columnSpan(4),

                                            Forms\Components\Textarea::make('hasil_singkat')
                                                ->label('Hasil Singkat')
                                                ->placeholder('Contoh: 1. Set modif IP existing...')
                                                ->rows(3)
                                                ->columnSpan(4),

                                            Forms\Components\Select::make('status')
                                                ->label('Status')
                                                ->options(['Selesai'=>'Selesai', 'In Progress'=>'In Progress', 'Pending'=>'Pending'])
                                                ->default('Selesai')
                                                ->columnSpan(2),

                                            Forms\Components\Textarea::make('remarks')
                                                ->label('Keterangan')
                                                ->rows(3)
                                                ->columnSpan(2),
                                        ])
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('+ Add More Task')
                            ])
                            ->default([
                                ['day' => 'Senin', 'task_date' => Carbon::now()->startOfWeek()->format('Y-m-d'), 'tasks' => [['task_description' => null, 'hasil_singkat' => null, 'status' => 'Selesai', 'remarks' => null]]],
                                ['day' => 'Selasa', 'task_date' => Carbon::now()->startOfWeek()->addDays(1)->format('Y-m-d'), 'tasks' => [['task_description' => null, 'hasil_singkat' => null, 'status' => 'Selesai', 'remarks' => null]]],
                                ['day' => 'Rabu', 'task_date' => Carbon::now()->startOfWeek()->addDays(2)->format('Y-m-d'), 'tasks' => [['task_description' => null, 'hasil_singkat' => null, 'status' => 'Selesai', 'remarks' => null]]],
                                ['day' => 'Kamis', 'task_date' => Carbon::now()->startOfWeek()->addDays(3)->format('Y-m-d'), 'tasks' => [['task_description' => null, 'hasil_singkat' => null, 'status' => 'Selesai', 'remarks' => null]]],
                                ['day' => 'Jumat', 'task_date' => Carbon::now()->startOfWeek()->addDays(4)->format('Y-m-d'), 'tasks' => [['task_description' => null, 'hasil_singkat' => null, 'status' => 'Selesai', 'remarks' => null]]],
                            ])
                            ->addable(false)->deletable(false)
                            ->itemLabel(fn (array $state): ?string => "✨ HARI " . strtoupper($state['day'] ?? ''))
                            ->collapsible()
                            ->columnSpanFull()
                    ]),

                Forms\Components\Section::make('Rencana Pekerjaan Minggu Depan')
                    ->schema([
                        Forms\Components\Repeater::make('plannedTasks')
                            ->relationship('plannedTasks')
                            ->extraAttributes(['class' => 'glass-repeater'])
                            ->label('')
                            ->schema([
                                Forms\Components\Hidden::make('day'),
                                Forms\Components\Hidden::make('deadline'),

                                Forms\Components\Repeater::make('tasks')
                                    ->label('Daftar Rencana')
                                    ->schema([
                                        Forms\Components\Grid::make(12)->schema([
                                            Forms\Components\Textarea::make('plan_name')
                                                ->label('Rencana Pekerjaan')
                                                ->placeholder('Rencana minggu depan...')
                                                ->rows(2)
                                                ->columnSpan(4),

                                            Forms\Components\TextInput::make('target')
                                                ->label('Target')
                                                ->placeholder('Target penyelesaian...')
                                                ->columnSpan(3),

                                            Forms\Components\Select::make('priority')
                                                ->label('Prioritas')
                                                ->options([
                                                    'High' => '🔴 High',
                                                    'Medium' => '🟡 Medium',
                                                    'Low' => '🟢 Low',
                                                ])
                                                ->default('Medium')
                                                ->columnSpan(2),

                                            Forms\Components\Textarea::make('notes')
                                                ->label('Catatan')
                                                ->rows(2)
                                                ->columnSpan(3),
                                        ])
                                    ])
                                    ->defaultItems(1)
                                    ->addActionLabel('+ Add More Plan')
                            ])
                            ->default([
                                ['day' => 'Senin', 'deadline' => Carbon::now()->startOfWeek()->addDays(7)->format('Y-m-d'), 'tasks' => [['plan_name' => null, 'target' => null, 'priority' => 'Medium', 'notes' => null]]],
                                ['day' => 'Selasa', 'deadline' => Carbon::now()->startOfWeek()->addDays(8)->format('Y-m-d'), 'tasks' => [['plan_name' => null, 'target' => null, 'priority' => 'Medium', 'notes' => null]]],
                                ['day' => 'Rabu', 'deadline' => Carbon::now()->startOfWeek()->addDays(9)->format('Y-m-d'), 'tasks' => [['plan_name' => null, 'target' => null, 'priority' => 'Medium', 'notes' => null]]],
                                ['day' => 'Kamis', 'deadline' => Carbon::now()->startOfWeek()->addDays(10)->format('Y-m-d'), 'tasks' => [['plan_name' => null, 'target' => null, 'priority' => 'Medium', 'notes' => null]]],
                                ['day' => 'Jumat', 'deadline' => Carbon::now()->startOfWeek()->addDays(11)->format('Y-m-d'), 'tasks' => [['plan_name' => null, 'target' => null, 'priority' => 'Medium', 'notes' => null]]],
                            ])
                            ->addable(false)->deletable(false)
                            ->itemLabel(fn (array $state): ?string => "🎯 RENCANA " . strtoupper($state['day'] ?? '') . " - " . (\Carbon\Carbon::parse($state['deadline'] ?? null)->translatedFormat('d M Y')))
                            ->collapsible()
                            ->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->timezone('Asia/Jakarta')->translatedFormat('l, d M Y - h:i A'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')->label('Periode Mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('end_date')->label('Periode Selesai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('pic_name')->label('PIC')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'warning',
                        'Submitted' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                // 💡 FIX: Tambahkan ->slideOver() agar panel muncul dari samping (tidak terpotong atasnya)
                Tables\Actions\ViewAction::make()->modalWidth('7xl')->slideOver(),
                Tables\Actions\EditAction::make()->modalWidth('7xl')->slideOver()->successNotificationTitle('Laporan Tersimpan! 🟢'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ExportBulkAction::make()
                    ->exporter(\App\Filament\Exports\PersonalItReportExporter::class)
                    ->label('Export to Excel (CSV)')
                    ->icon('heroicon-o-document-arrow-down')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonalItReports::route('/'),
        ];
    }
}
