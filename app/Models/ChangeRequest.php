<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChangeRequestResource\Pages;
use App\Models\ChangeRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChangeRequestResource extends Resource
{
    protected static ?string $model = ChangeRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationLabel = 'ITSM / Change';
    protected static ?string $navigationGroup = 'IT MANAGEMENT';
    protected static ?int $navigationSort = 3;

    // HANYA TIM IT YANG BISA MENGAKSES MENU INI
    public static function canViewAny(): bool
    {
        return auth()->user()->is_it_team == 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Formulir Pengajuan Perubahan (Change Request)')
                    ->icon('heroicon-o-document-plus')
                    ->schema([
                        Forms\Components\Hidden::make('requester_id')
                            ->default(fn () => auth()->user()->employee_id ?? auth()->id()),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Perubahan')
                            ->placeholder('Contoh: Upgrade RAM Server Database 32GB -> 128GB')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('description')
                            ->label('Detail Perubahan')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('justification')
                            ->label('Justifikasi (Alasan Bisnis)')
                            ->placeholder('Mengapa perubahan ini diperlukan?')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('fallback_plan')
                            ->label('Rencana Rollback (Jika Gagal)')
                            ->placeholder('Apa yang dilakukan jika perubahan ini menyebabkan sistem mati?')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('risk_level')
                            ->label('Tingkat Risiko')
                            ->options([
                                'Low' => '🟢 Rendah',
                                'Medium' => '🟡 Sedang',
                                'High' => '🟠 Tinggi',
                                'Critical' => '🔴 Kritis',
                            ])
                            ->default('Low')
                            ->required(),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('planned_start_date')->label('Rencana Mulai')->required(),
                            Forms\Components\DateTimePicker::make('planned_end_date')->label('Rencana Selesai')->required(),
                        ]),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->weight('bold')->limit(40),
                Tables\Columns\TextColumn::make('requester.full_name')->label('Diajukan Oleh')->icon('heroicon-m-user'),

                Tables\Columns\BadgeColumn::make('risk_level')
                    ->label('Risiko')
                    ->colors(['success' => 'Low', 'warning' => 'Medium', 'danger' => 'High', 'danger' => 'Critical']),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Approval')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Draft', 2 => 'Pending Approval', 3 => 'Approved', 4 => 'Rejected', 5 => 'Implemented'
                    })
                    ->color(fn ($state) => match ($state) {
                        1 => 'gray', 2 => 'warning', 3 => 'success', 4 => 'danger', 5 => 'info'
                    }),

                Tables\Columns\TextColumn::make('manager.full_name')
                    ->label('Disetujui Oleh')
                    ->placeholder('Belum di-approve'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),

                // =========================================================
                // LOGIC MAGIS: TOMBOL APPROVAL KHUSUS UNTUK IT MANAGER
                // =========================================================
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    // Hanya Mas Hendri (Manager) yang bisa melihat tombol ini dan statusnya harus Pending/Draft
                    ->visible(fn ($record) => in_array($record->status, [1, 2]) && auth()->user()->full_name === 'HENDRI SETIO PRAKOSO')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 3, // Approved
                            'manager_id' => auth()->user()->employee_id,
                        ]);
                    })
                    ->successNotificationTitle('Perubahan Disetujui!'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, [1, 2]) && auth()->user()->full_name === 'HENDRI SETIO PRAKOSO')
                    ->action(function ($record) {
                        $record->update(['status' => 4]); // Rejected
                    })
                    ->successNotificationTitle('Perubahan Ditolak!'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChangeRequests::route('/'),
            'create' => Pages\CreateChangeRequest::route('/create'),
            'edit' => Pages\EditChangeRequest::route('/{record}/edit'),
        ];
    }
}
