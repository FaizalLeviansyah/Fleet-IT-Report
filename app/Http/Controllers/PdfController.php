<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\Vessel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PdfController extends Controller
{
    // =======================================================
    // 1. FUNGSI UNTUK CETAK SUMMARY OPS
    // =======================================================
    public function generateSummary(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');

        $from = $request->query('from');
        $to = $request->query('to');

        if (!$from || !$to) abort(400, 'Rentang tanggal tidak valid.');

        $startDate = Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('d/m/Y', $to)->endOfDay();

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

    // =======================================================
    // 2. FUNGSI UNTUK CETAK BULK EXPORT LAPORAN
    // =======================================================
    public function bulkExportLaporan(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '300');

        $key = $request->query('key');
        $ids = Cache::get($key);

        if (!$ids) {
            abort(404, 'Sesi cetak PDF kedaluwarsa atau tidak valid. Silakan ulangi checklist dari sistem.');
        }

        $allVessels = Vessel::all();
        $laporans = Laporan::with('gambars')->whereIn('id', $ids)->orderBy('lokasi', 'asc')->orderBy('waktu_kejadian', 'desc')->get();
        $groupedLaporans = $laporans->groupBy('lokasi');

        $vesselCustomLabels = [];
        foreach ($allVessels as $vessel) {
            $vesselCustomLabels[$vessel->vessel_name] = $vessel->cctv_names ?? ['AJG'=>'AJG','BRT'=>'BRT','CCR'=>'CCR','ECR'=>'ECR','WKN'=>'WKN','WKR'=>'WKR'];
        }

        $pdf = Pdf::loadView('pdf.bulk-laporan', [
            'groupedLaporans' => $groupedLaporans,
            'vesselCustomLabels' => $vesselCustomLabels
        ])
        ->setPaper('a4', 'portrait')
        ->setWarnings(false)
        ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('LAPORAN_CCTV_BULK_' . now()->format('Ymd_His') . '.pdf');
    }
}
