<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VesselResource\Pages;
use App\Models\Vessel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;

class VesselResource extends Resource
{
    protected static ?string $model = Vessel::class;

    // --- PENGATURAN SIDEBAR ---
    protected static ?string $navigationIcon = 'heroicon-o-map-pin'; // Ikon Lokasi/Kapal
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Vessel Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Informasi Kapal')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Nama Perusahaan (Company)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('vessel_name')
                            ->label('Nama Kapal')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('pic_name')
                            ->label('Nama PIC Kapal')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('catatan')
                            ->label('Catatan / Spesifikasi Kapal')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Perusahaan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('vessel_name')
                    ->label('Nama Kapal')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('pic_name')
                    ->label('PIC')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Didaftarkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVessels::route('/'),
            'create' => Pages\CreateVessel::route('/create'),
            'edit' => Pages\EditVessel::route('/{record}/edit'),
        ];
    }
}
