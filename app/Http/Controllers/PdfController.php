<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Vessel;
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

        $allVessels = \App\Models\Vessel::orderBy('vessel_name', 'asc')->pluck('vessel_name')->toArray();

        $laporans = Laporan::with('gambars')
            ->whereBetween('waktu_kejadian', [$startDate, $endDate])
            ->orderBy('waktu_kejadian', 'desc')
            ->get();

        $groupedLaporans = $laporans->groupBy('lokasi');

        $totalKapal = count($allVessels);
        $activeVesselsCount = 0; // 👇 Tambahan Penghitung Kapal Aktif
        $offlineVesselsCount = 0; // 👇 Tambahan Penghitung Kapal Offline

        $stats = ['Clear' => 0, 'Blur' => 0, 'NA' => 0];
        $channels = ['status_ajg', 'status_brt', 'status_ccr', 'status_ecr', 'status_wkn', 'status_wkr'];

        $auditTrail = [];

        foreach ($allVessels as $vesselName) {
            $laps = $groupedLaporans->get($vesselName, collect());
            $totalSnapshots = 0;
            $incidents = 0;

            if ($laps->count() > 0) {
                $activeVesselsCount++; // Hitung jika ada laporan
                foreach ($laps as $lap) {
                    $totalSnapshots += $lap->gambars->count();
                    foreach ($channels as $ch) {
                        $status = $lap->$ch ?? 'Clear';
                        if (isset($stats[$status])) $stats[$status]++;
                        if ($status !== 'Clear') $incidents++;
                    }
                }
            } else {
                $offlineVesselsCount++; // Hitung jika kosong
            }

            $auditTrail[] = [
                'armada' => $vesselName,
                'total_laporan' => $laps->count(),
                'total_snapshot' => $totalSnapshots,
                'insiden' => $incidents,
                'status' => $laps->count() > 0 ? 'Aktif' : 'Offline / No Data'
            ];
        }

        $totalCams = array_sum($stats) ?: 1;
        $uptimePercentage = round(($stats['Clear'] / $totalCams) * 100, 1);
        $downtimeCount = $stats['Blur'] + $stats['NA'];

        $pdf = Pdf::loadView('pdf.summary-ops', [
            'groupedLaporans' => $groupedLaporans,
            'from' => $from,
            'to' => $to,
            'totalKapal' => $totalKapal,
            'activeVesselsCount' => $activeVesselsCount, // Lempar ke Blade
            'offlineVesselsCount' => $offlineVesselsCount, // Lempar ke Blade
            'uptimePercentage' => $uptimePercentage,
            'downtimeCount' => $downtimeCount,
            'auditTrail' => $auditTrail
        ])
        ->setPaper('a4', 'portrait')
        ->setWarnings(false)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('SUMMARY_OPS.pdf');
    }
}
