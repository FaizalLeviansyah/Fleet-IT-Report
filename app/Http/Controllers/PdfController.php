<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function generateSummary(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');

        $from = $request->query('from');
        $to = $request->query('to');

        if (!$from || !$to) abort(400, 'Rentang tanggal tidak valid.');

        $startDate = Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', $to)->endOfDay();

        $laporans = Laporan::with('gambars')
            ->whereBetween('waktu_kejadian', [$startDate, $endDate])
            ->orderBy('lokasi', 'asc')
            ->orderBy('waktu_kejadian', 'desc')
            ->get();

        $groupedLaporans = $laporans->groupBy('lokasi');

        // 👇 ANALITIK: Menghitung Metrik Executive Summary
        $totalLaporan = $laporans->count();
        $totalKapal = $groupedLaporans->count();

        $stats = ['Clear' => 0, 'Blur' => 0, 'NA' => 0];
        $channels = ['status_ajg', 'status_brt', 'status_ccr', 'status_ecr', 'status_wkn', 'status_wkr'];

        foreach ($laporans as $lap) {
            foreach ($channels as $ch) {
                $status = $lap->$ch ?? 'Clear'; // Fallback jika data lama kosong
                if (isset($stats[$status])) $stats[$status]++;
            }
        }

        $totalCams = array_sum($stats) ?: 1; // Hindari pembagian 0
        $uptimePercentage = round(($stats['Clear'] / $totalCams) * 100, 1);
        $downtimeCount = $stats['Blur'] + $stats['NA'];

        $pdf = Pdf::loadView('pdf.summary-ops', [
            'groupedLaporans' => $groupedLaporans,
            'from' => $from,
            'to' => $to,
            // Kirim data analitik ke PDF
            'totalLaporan' => $totalLaporan,
            'totalKapal' => $totalKapal,
            'uptimePercentage' => $uptimePercentage,
            'downtimeCount' => $downtimeCount,
            'stats' => $stats
        ])
        ->setPaper('a4', 'portrait')
        ->setWarnings(false)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $filename = 'SUMMARY_OPS_' . str_replace('/', '', $from) . '-' . str_replace('/', '', $to) . '.pdf';
        return $pdf->stream($filename);
    }
}
