<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KnowledgeBaseResource\Pages;
use App\Models\KnowledgeBase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseResource extends Resource
{
    protected static ?string $model = KnowledgeBase::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'ITSM / Knowledge Base';
    protected static ?string $navigationGroup = 'IT Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Artikel Pengetahuan';
    protected static ?string $pluralModelLabel = 'Basis Pengetahuan (FAQ)';

    // =====================================================================
    // LOGIC MAGIS 1: USER BIASA HANYA BISA MELIHAT ARTIKEL "PUBLIC"
    // =====================================================================
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika yang login BUKAN tim IT, sembunyikan artikel yang is_public = false
        if (auth()->user()->is_it_team != 1) {
            $query->where('is_public', true);
        }

        return $query;
    }

    // =====================================================================
    // LOGIC MAGIS 2: KUNCI TOMBOL CREATE, EDIT, DELETE (HANYA UNTUK IT)
    // =====================================================================
    public static function canCreate(): bool
    {
        return auth()->user()->is_it_team == 1;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->is_it_team == 1;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->is_it_team == 1;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Artikel')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Hidden::make('author_id')
                            ->default(fn () => auth()->user()->employee_id ?? auth()->id()),

                        Forms\Components\TextInput::make('title')
                            ->label('Judul Artikel / Masalah')
                            ->placeholder('Contoh: Cara Mengatasi Printer Offline')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'Jaringan & Internet' => '🌐 Jaringan & Internet',
                                'Hardware & Printer' => '🖨️ Hardware & Printer',
                                'Software & Aplikasi' => '💻 Software & Aplikasi',
                                'Akun & Akses' => '🔐 Akun & Akses',
                                'Lainnya' => '📌 Lainnya',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_public')
                            ->label('Publik (Bisa dibaca oleh User Biasa)')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Artikel / SOP / Tutorial')
                            ->placeholder('Tuliskan langkah-langkah penyelesaian di sini...')
                            ->required()
                            ->fileAttachmentsDirectory('knowledge-base')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $isIT = auth()->user()->is_it_team == 1;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.full_name')
                    ->label('Ditulis Oleh')
                    ->icon('heroicon-m-user-circle'),

                Tables\Columns\IconColumn::make('is_public')
                    ->label('Status Publik')
                    ->boolean()
                    ->trueIcon('heroicon-o-globe-americas')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->visible($isIT), // Kolom ini disembunyikan untuk user biasa

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                // Semua orang bisa klik "Baca"
                Tables\Actions\ViewAction::make()->label('Baca Artikel'),

                // Edit & Delete otomatis terkunci oleh fungsi canEdit & canDelete di atas
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKnowledgeBases::route('/'),
            'create' => Pages\CreateKnowledgeBase::route('/create'),
            'view' => Pages\ViewKnowledgeBase::route('/{record}'),
            'edit' => Pages\EditKnowledgeBase::route('/{record}/edit'),
        ];
    }
}
