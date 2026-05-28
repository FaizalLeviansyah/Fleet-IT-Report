<?php

namespace App\Filament\Resources;

use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
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
                // SECTION 1: HEADER
                Forms\Components\Section::make('Informasi Periode Laporan')
                    ->description('Tentukan rentang tanggal laporan dan PIC.')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Awal')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Akhir')
                            ->required(),
                        Forms\Components\Select::make('pic_name')
                            ->label('PIC Laporan')
                            ->options(['Levi' => 'Levi', 'Farhan' => 'Farhan'])
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options(['Draft' => 'Draft', 'Submitted' => 'Submitted'])
                            ->default('Draft')
                            ->required(),
                    ])->columns(4),

                // SECTION 2: ACTUAL TASKS (TAMPILAN EXCEL)
                Forms\Components\Section::make('Pekerjaan Yang Dilakukan (Actual Tasks)')
                    ->schema([
                        TableRepeater::make('actualTasks')
                            ->relationship()
                            ->headers([
                                Header::make('Hari')->width('130px'),
                                Header::make('Tanggal')->width('160px'),
                                Header::make('Pekerjaan Yang Dilakukan'),
                                Header::make('Status')->width('140px'),
                                Header::make('Keterangan / Remarks'),
                            ])
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->options([
                                        'Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu',
                                        'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu', 'Minggu'=>'Minggu'
                                    ])->disableLabel()->required(),
                                Forms\Components\DatePicker::make('date')
                                    ->disableLabel()->required(),
                                Forms\Components\TextInput::make('task_description')
                                    ->disableLabel()->required(),
                                Forms\Components\Select::make('status')
                                    ->options(['Selesai'=>'Selesai', 'In Progress'=>'In Progress', 'Pending'=>'Pending'])
                                    ->disableLabel()->required(),
                                Forms\Components\TextInput::make('remarks')
                                    ->disableLabel(),
                            ])
                            ->emptyLabel('Belum ada data pekerjaan.')
                            ->addActionLabel('+ Tambah Pekerjaan')
                            ->columnSpanFull()
                    ]),

                // SECTION 3: PLANNED TASKS
                Forms\Components\Section::make('Rencana Pekerjaan Minggu Depan')
                    ->schema([
                        TableRepeater::make('plannedTasks')
                            ->relationship()
                            ->headers([
                                Header::make('Hari')->width('130px'),
                                Header::make('Tanggal')->width('160px'),
                                Header::make('Rencana Pekerjaan'),
                            ])
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->options([
                                        'Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu',
                                        'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu', 'Minggu'=>'Minggu'
                                    ])->disableLabel()->required(),
                                Forms\Components\DatePicker::make('date')
                                    ->disableLabel()->required(),
                                Forms\Components\TextInput::make('task_description')
                                    ->disableLabel()->required(),
                            ])
                            ->emptyLabel('Belum ada rencana kerja.')
                            ->addActionLabel('+ Tambah Rencana')
                            ->columnSpanFull()
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('start_date')->label('Periode Mulai')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('end_date')->label('Periode Selesai')->date('d M Y'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'warning',
                        'Submitted' => 'success',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(), // Sekarang akan mengarah ke halaman Edit
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPersonalItReports::route('/'),
            'create' => Pages\CreatePersonalItReport::route('/create'),
            'edit' => Pages\EditPersonalItReport::route('/{record}/edit'),
        ];
    }
}
