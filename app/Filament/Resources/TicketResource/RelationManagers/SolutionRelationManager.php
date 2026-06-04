<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SolutionRelationManager extends RelationManager
{
    protected static string $relationship = 'solution';
    protected static ?string $title = 'Solusi & Persetujuan (Approval)';
    protected static ?string $icon = 'heroicon-o-check-badge';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->user()->employee_id ?? auth()->id()),

                Forms\Components\RichEditor::make('content')
                    ->label('Solusi / Penjelasan Penyelesaian')
                    ->placeholder('Jelaskan tindakan yang telah dilakukan untuk menyelesaikan masalah ini...')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        // Deteksi apakah yang login adalah Teknisi IT
        $isIT = in_array(auth()->user()->full_name, [
            'FAIZAL LEVIANSYAH', 'FARHAN ARIF INDIARTO', 'HENDRI SETIO PRAKOSO'
        ]);

        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Diberikan Oleh (Teknisi)')
                    ->icon('heroicon-m-check-badge')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('content')
                    ->label('Detail Solusi')
                    ->html()
                    ->wrap(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status Persetujuan')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => '⏳ Menunggu Persetujuan User',
                        2 => '✅ Solusi Disetujui (Closed)',
                        3 => '❌ Solusi Ditolak (Assigned)',
                        default => 'Unknown',
                    })
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'warning',
                        2 => 'success',
                        3 => 'danger',
                        default => 'gray',
                    }),
            ])
            ->headerActions([
                // HANYA TEKNISI YANG BISA MEMBERIKAN SOLUSI
                Tables\Actions\CreateAction::make()
                    ->label('Berikan Solusi')
                    ->icon('heroicon-o-light-bulb')
                    ->visible($isIT)
                    ->successNotificationTitle('Solusi berhasil dikirim!')
                    ->after(function (RelationManager $livewire) {
                        // LOGIC GLPI: Saat IT kasih solusi, status tiket utama otomatis jadi "Solved"
                        $livewire->getOwnerRecord()->update(['status' => Ticket::STATUS_SOLVED]);
                    }),
            ])
            ->actions([
                // ==============================================================
                // 🚨 TOMBOL SAKTI: HANYA MUNCUL UNTUK USER PEMOHON (REQUESTER) 🚨
                // ==============================================================

                Tables\Actions\Action::make('approve')
                    ->label('Setujui Solusi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Solusi?')
                    ->modalDescription('Apakah solusi ini sudah menyelesaikan masalah Anda? Tiket akan ditutup.')
                    // Cek: Status harus "Menunggu" (1) DAN yang login harus si pembuat tiket
                    ->visible(fn ($record, RelationManager $livewire) => $record->status == 1 && auth()->user()->employee_id == $livewire->getOwnerRecord()->requester_id)
                    ->action(function ($record, RelationManager $livewire) {
                        $record->update(['status' => 2]); // Status Solusi: Disetujui
                        $livewire->getOwnerRecord()->update(['status' => Ticket::STATUS_CLOSED]); // Status Tiket: Closed
                    })
                    ->successNotificationTitle('Tiket Berhasil Ditutup!'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak Solusi')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Solusi?')
                    ->modalDescription('Silakan tuliskan alasan mengapa solusi ini belum menyelesaikan masalah Anda.')
                    ->form([
                        // Memaksa user mengisi alasan penolakan
                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('Contoh: Printer masih macet saat mencetak lebih dari 10 lembar...')
                            ->required()
                    ])
                    ->visible(fn ($record, RelationManager $livewire) => $record->status == 1 && auth()->user()->employee_id == $livewire->getOwnerRecord()->requester_id)
                    ->action(function (array $data, $record, RelationManager $livewire) {
                        // 1. Status solusi diubah menjadi Ditolak
                        $record->update(['status' => 3]);

                        // 2. Status tiket dikembalikan ke Assigned
                        $ticket = $livewire->getOwnerRecord();
                        $ticket->update(['status' => Ticket::STATUS_ASSIGNED]);

                        // 3. Otomatis buatkan komentar/tindak lanjut baru berisi alasan penolakan
                        \App\Models\TicketFollowup::create([
                            'ticket_id' => $ticket->id,
                            'user_id'   => auth()->user()->employee_id ?? auth()->id(),
                            'content'   => '<strong>❌ Solusi Ditolak:</strong><br>' . $data['reason'],
                            'is_private'=> false,
                        ]);
                    })
                    ->successNotificationTitle('Solusi ditolak! Alasan dikirim ke Teknisi.'),
            ]);
    }
}
