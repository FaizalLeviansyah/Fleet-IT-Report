<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('vessel_id')
                    ->numeric(),
                Forms\Components\TextInput::make('category_id')
                    ->numeric(),
                Forms\Components\TextInput::make('location_id')
                    ->numeric(),
                Forms\Components\TextInput::make('asset_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('asset_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_entity')
                    ->maxLength(255),
                Forms\Components\TextInput::make('manufacturer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->maxLength(255),
                Forms\Components\TextInput::make('hardware_uuid')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ip_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mac_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('serial_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('Active'),
                Forms\Components\TextInput::make('os_version')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpu_model')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_ram')
                    ->maxLength(255),
                Forms\Components\TextInput::make('disk_space')
                    ->maxLength(255),
                Forms\Components\TextInput::make('current_user')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('last_boot_time'),
                Forms\Components\TextInput::make('software_list'),
                Forms\Components\DateTimePicker::make('last_seen'),
                Forms\Components\TextInput::make('group_name')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vessel_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asset_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_entity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hardware_uuid')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mac_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('os_version')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpu_model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_ram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disk_space')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_user')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_boot_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_seen')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group_name')
                    ->searchable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
