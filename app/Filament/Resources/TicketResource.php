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
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?string $navigationLabel = 'Manajemen Tiket';
    protected static ?int $navigationSort = 1;

    // 💡 SECURITY (RBAC): Sembunyikan menu ini dari Client (Owner)
    public static function canViewAny(): bool
    {
        return strtolower(auth()->user()->role) !== 'owner';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 1)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kendala')
                    ->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('Nomor Tiket')
                            ->disabled(),

                        Forms\Components\Select::make('user_id')
                            ->label('Pelapor (Requester)')
                            ->options(User::pluck('full_name', 'employee_id'))
                            ->searchable()
                            ->disabled(),

                        Forms\Components\TextInput::make('name')
                            ->label('Subjek / Judul')
                            ->columnSpanFull()
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Detail Masalah')
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled(),
                    ])->columns(2),

                Forms\Components\Section::make('Penugasan & Status IT')
                    ->schema([
                        Forms\Components\Select::make('assigned_to')
                            ->label('Teknisi IT (Assignee)')
                            ->options(User::where('is_it_team', 1)->orWhere('role', 'admin')->pluck('full_name', 'employee_id'))
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('priority')
                            ->label('Tingkat Prioritas')
                            ->options([
                                1 => 'Low (Rendah)',
                                2 => 'Medium (Sedang)',
                                3 => 'High (Mendesak)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Tiket')
                            ->options([
                                1 => 'New (Baru)',
                                2 => 'Assigned (Diberikan ke Teknisi)',
                                3 => 'In Progress (Sedang Dikerjakan)',
                                4 => 'Pending (Menunggu User/Vendor)',
                                5 => 'Resolved (Selesai - Menunggu Approval)',
                                6 => 'Closed (Ditutup)',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('requester.full_name')
                    ->label('Pelapor')
                    ->searchable()
                    ->description(fn (Ticket $record): string => $record->requester->department->department_name ?? 'Unknown Dept'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Kendala')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(fn (Ticket $record): string => $record->name),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'gray',
                        2 => 'warning',
                        3 => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'New',
                        2 => 'Assigned',
                        3 => 'In Progress',
                        4 => 'Pending',
                        5 => 'Resolved',
                        6 => 'Closed',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'info',
                        4 => 'gray',
                        5, 6 => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('technician.full_name')
                    ->label('Teknisi')
                    ->icon('heroicon-m-user-circle')
                    ->placeholder('Belum Diambil')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        1 => 'New',
                        2 => 'Assigned',
                        3 => 'In Progress',
                        4 => 'Pending',
                        5 => 'Resolved',
                        6 => 'Closed',
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('take_ticket')
                    ->label('Ambil Tiket')
                    ->icon('heroicon-o-hand-raised')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Ambil Alih Tiket Ini?')
                    ->modalDescription('Anda akan ditetapkan sebagai teknisi penanggung jawab (PIC) untuk tiket ini dan statusnya akan berubah menjadi In Progress.')
                    ->hidden(fn (Ticket $record) => $record->assigned_to !== null)
                    ->action(function (Ticket $record) {
                        $record->update([
                            'assigned_to' => Auth::id(),
                            'status' => 3,
                        ]);
                        Notification::make()
                            ->title('Tiket Berhasil Diambil!')
                            ->body('Anda sekarang adalah PIC untuk tiket ini.')
                            ->success()
                            ->send();
                    }),

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
            TicketResource\RelationManagers\FollowupsRelationManager::class,
            TicketResource\RelationManagers\SolutionRelationManager::class,
            TicketResource\RelationManagers\TasksRelationManager::class,
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
