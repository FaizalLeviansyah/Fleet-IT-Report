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

        // 💡 Tarik SELURUH OBJEK Master Kapal (Bukan cuma namanya)
        $allVessels = Vessel::orderBy('vessel_name', 'asc')->get();

        $laporans = Laporan::with('gambars')
            ->whereBetween('waktu_kejadian', [$startDate, $endDate])
            ->orderBy('waktu_kejadian', 'desc')
            ->get();

        $groupedLaporans = $laporans->groupBy('lokasi');

        $totalKapal = $allVessels->count();
        $activeVesselsCount = 0;
        $offlineVesselsCount = 0;

        $stats = ['Clear' => 0, 'Blur' => 0, 'NA' => 0];
        $channels = ['status_ajg', 'status_brt', 'status_ccr', 'status_ecr', 'status_wkn', 'status_wkr'];

        $auditTrail = [];
        $vesselCustomLabels = [];
        $default_labels = ['AJG'=>'AJG','BRT'=>'BRT','CCR'=>'CCR','ECR'=>'ECR','WKN'=>'WKN','WKR'=>'WKR'];

        foreach ($allVessels as $vessel) {
            $vesselName = $vessel->vessel_name;

            // 💡 Tarik Data dari Kolom cctv_names di Master Data
            $vesselCustomLabels[$vesselName] = $vessel->cctv_names ?? $default_labels;

            $laps = $groupedLaporans->get($vesselName, collect());
            $totalSnapshots = 0;
            $incidents = 0;

            if ($laps->count() > 0) {
                $activeVesselsCount++;
                foreach ($laps as $lap) {
                    $totalSnapshots += $lap->gambars->count();
                    foreach ($channels as $ch) {
                        $status = $lap->$ch ?? 'Clear';
                        if (isset($stats[$status])) $stats[$status]++;
                        if ($status !== 'Clear') $incidents++;
                    }
                }
            } else {
                $offlineVesselsCount++;
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
            'activeVesselsCount' => $activeVesselsCount,
            'offlineVesselsCount' => $offlineVesselsCount,
            'uptimePercentage' => $uptimePercentage,
            'downtimeCount' => $downtimeCount,
            'auditTrail' => $auditTrail,
            'vesselCustomLabels' => $vesselCustomLabels
        ])
        ->setPaper('a4', 'portrait')
        ->setWarnings(false)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('SUMMARY_OPS.pdf');
    }
}
