<?php

namespace App\Filament\Exports;

use App\Models\Laporan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LaporanExporter extends Exporter
{
    protected static ?string $model = Laporan::class;

    public static function getColumns(): array
    {
        return [
            // Sesuaikan nama di dalam make('') dengan nama kolom di database Anda
            ExportColumn::make('id')->label('ID Tiket'),
            ExportColumn::make('judul_laporan')->label('Judul Laporan'),
            ExportColumn::make('nama_kapal')->label('Lokasi / Kapal'),
            ExportColumn::make('status')->label('Status CCTV'),
            ExportColumn::make('deskripsi')->label('Keterangan / Kendala'),
            ExportColumn::make('created_at')->label('Tanggal Dibuat')->date('d-m-Y'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor Laporan CCTV Anda telah selesai dan file siap diunduh! 🎉';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}
