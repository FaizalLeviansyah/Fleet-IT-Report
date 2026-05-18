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

    // --- PENGATURAN SIDEBAR (Cukup 1x saja) ---
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Vessel Management';
    protected static ?int $navigationSort = 1;

    // --- FITUR KEAMANAN: HANYA ADMIN YANG BISA LIHAT MENU INI ---
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

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

                        // DROPDOWN DINAMIS KE DATABASE MASTER HRD
                        \Filament\Forms\Components\Select::make('pic_name')
                            ->label('Nama PIC Kapal')
                            ->options(
                                \App\Models\Employee::where('is_active', 1)->pluck('full_name', 'full_name')
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

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
            ->filters([])
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
