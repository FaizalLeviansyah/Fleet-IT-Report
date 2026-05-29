<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';
    protected static ?string $title = 'Tugas Teknisi (Tasks)';
    protected static ?string $icon = 'heroicon-o-clipboard-document-check';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->user()->employee_id ?? auth()->id()),

                Forms\Components\RichEditor::make('content')
                    ->label('Deskripsi Pekerjaan')
                    ->placeholder('Contoh: Melakukan install ulang OS dan setting IP...')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\TextInput::make('actiontime')
                        ->label('Durasi (Menit)')
                        ->numeric()
                        ->default(0)
                        ->suffix('Menit')
                        ->required(),

                    Forms\Components\Select::make('state')
                        ->label('Status Pekerjaan')
                        ->options([
                            '1' => 'To Do (Akan Dikerjakan)',
                            '2' => 'Doing (Sedang Dikerjakan)',
                            '3' => 'Done (Selesai)',
                        ])
                        ->default('1')
                        ->required(),

                    Forms\Components\Toggle::make('is_private')
                        ->label('Private (Internal)')
                        ->default(false),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Teknisi')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('content')
                    ->label('Pekerjaan')
                    ->html()
                    ->limit(50),

                Tables\Columns\TextColumn::make('actiontime')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state) => $state . ' Menit')
                    ->badge()
                    ->color('info'),

                Tables\Columns\BadgeColumn::make('state')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'To Do',
                        '2' => 'Doing',
                        '3' => 'Done',
                        default => 'Unknown',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'danger',
                        '2' => 'warning',
                        '3' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Pekerjaan')->icon('heroicon-o-plus'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
