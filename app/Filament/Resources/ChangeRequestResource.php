<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChangeRequestResource\Pages;
use App\Models\ChangeRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChangeRequestResource extends Resource
{
    protected static ?string $model = ChangeRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?string $navigationLabel = 'Change Request';
    protected static ?int $navigationSort = 3;

    // 💡 SECURITY (RBAC): Sembunyikan menu ini dari Client (Owner)
    public static function canViewAny(): bool
    {
        return strtolower(auth()->user()->role) !== 'owner';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Perubahan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Perubahan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul Perubahan')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
