<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary Ops PDF</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 0; }

        /* COVER PAGE STYLES */
        .cover-page { min-height: 100vh; padding: 40px 20px; page-break-after: always; }
        .header { text-align: center; border-bottom: 3px solid #3b82f6; padding-bottom: 25px; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 32px; color: #0f172a; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 10px 0 0; color: #64748b; font-size: 14px; font-weight: bold; letter-spacing: 1px; }

        .exec-summary { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .exec-summary td { width: 33.33%; padding: 30px 15px; text-align: center; border: 1px solid #e2e8f0; background-color: #f8fafc; border-radius: 8px;}
        .exec-summary .title { font-size: 12px; color: #64748b; text-transform: uppercase; margin-bottom: 10px; display: block; letter-spacing: 1px; }
        .exec-summary .value { font-size: 36px; font-weight: black; color: #0f172a; }
        .exec-summary .value.green { color: #16a34a; }
        .exec-summary .value.red { color: #dc2626; }

        .audit-title { font-size: 16px; font-weight: bold; color: #1e293b; margin-bottom: 15px; text-transform: uppercase; border-left: 4px solid #3b82f6; padding-left: 10px;}
        .audit-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 11px; }
        .audit-table th { background-color: #1e293b; color: #fff; padding: 12px; text-transform: uppercase; border: 1px solid #1e293b; }
        .audit-table td { border: 1px solid #cbd5e1; padding: 12px; text-align: center; }
        .audit-table td.text-left { text-align: left; font-weight: bold; }
        .audit-table tr:nth-child(even) { background-color: #f8fafc; }
        .audit-table tr.offline td { color: #94a3b8; font-style: italic; background-color: #fef2f2;}
        .note { font-size: 10px; color: #64748b; font-style: italic; text-align: left; margin-top: 10px; }

        /* DATA LAPORAN STYLES */
        .laporan-card { border: 1px solid #cbd5e1; margin-bottom: 25px; page-break-inside: avoid; border-radius: 4px; background-color: #fff; overflow: hidden; }
        .laporan-header-table { width: 100%; background-color: #1e293b; color: #fff; border-collapse: collapse; }
        .laporan-header-table td { padding: 10px 15px; font-size: 13px; font-weight: bold; text-transform: uppercase; }

        .status-container { padding: 12px 15px; border-bottom: 1px dashed #cbd5e1; background-color: #f8fafc; }
        .status-badge { display: inline-block; padding: 3px 8px; font-size: 9px; font-weight: bold; margin-right: 5px; border-radius: 3px; color: #fff; }
        .bg-clear { background-color: #16a34a; }
        .bg-blur { background-color: #d97706; }
        .bg-na { background-color: #dc2626; }
        .info-text { font-size: 10px; color: #64748b; float: right; margin-top: 2px; font-style: italic; }

        .image-container { padding: 15px; text-align: left; }
        .image-box { display: inline-block; width: 31%; margin-right: 1.5%; margin-bottom: 15px; vertical-align: top; text-align: center; }
        .image-box img { width: 100%; height: 110px; object-fit: cover; border: 1px solid #cbd5e1; border-radius: 4px; background-color: #f1f5f9; }
        .image-channel { font-size: 10px; font-weight: bold; margin-top: 5px; background-color: #e2e8f0; padding: 3px 0; border-radius: 2px; color: #1e293b; }

        .text-section { padding: 0 15px 15px 15px; }
        .narrative-box { background-color: #f1f5f9; padding: 10px; border-left: 3px solid #64748b; font-size: 10px; }
        .catatan-box { background-color: #eff6ff; padding: 10px; border-left: 3px solid #3b82f6; margin-top: 8px; font-size: 10px; }
    </style>
</head>
<body>

    <div class="cover-page">
        <div class="header">
            <h1>SUMMARY OPS REPORT</h1>
            <p>PERIODE: {{ $from }} S/D {{ $to }}</p>
        </div>

        <table class="exec-summary">
            <tr>
                <td>
                    <span class="title">Status Armada (Total {{ $totalKapal }})</span>
                    <span class="value" style="font-size: 20px;">
                        <span class="green">{{ $activeVesselsCount }} Aktif</span> /
                        <span class="red">{{ $offlineVesselsCount }} Offline</span>
                    </span>
                </td>
                <td>
                    <span class="title">System Uptime</span>
                    <span class="value {{ $uptimePercentage >= 90 ? 'green' : 'red' }}">{{ $uptimePercentage }}%</span>
                </td>
                <td>
                    <span class="title">Insiden Kamera (Blur/NA)</span>
                    <span class="value red">{{ $downtimeCount }} Titik</span>
                </td>
            </tr>
        </table>

        <div class="audit-title">AUDIT TRAIL & LOG AKTIVITAS KAPAL</div>
        <table class="audit-table">
            <thead>
                <tr>
                    <th>Nama Armada</th>
                    <th>Status Sistem</th>
                    <th>Laporan Masuk</th>
                    <th>Kuantitas Snapshot</th>
                    <th>Insiden Terdeteksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditTrail as $audit)
                    <tr class="{{ $audit['status'] === 'Offline / No Data' ? 'offline' : '' }}">
                        <td class="text-left">ARMADA: {{ $audit['armada'] }}</td>
                        <td style="font-weight: bold; {{ $audit['status'] === 'Aktif' ? 'color: #16a34a;' : 'color: #dc2626;' }}">{{ $audit['status'] }}</td>
                        <td>{{ $audit['total_laporan'] }} Log</td>
                        <td>{{ $audit['total_snapshot'] }} Foto</td>
                        <td style="{{ $audit['insiden'] > 0 ? 'color: red; font-weight: bold;' : '' }}">
                            {{ $audit['insiden'] }} Titik
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="note">* Catatan: Armada dengan status "Offline / No Data" menunjukkan tidak ada rekaman/laporan CCTV yang diterima oleh server pusat pada rentang tanggal tersebut.</p>
    </div>

    @foreach($groupedLaporans as $lokasi => $laporans)
        @foreach($laporans as $laporan)
            <div class="laporan-card">
                <table class="laporan-header-table">
                    <tr>
                        <td align="left">ARMADA: {{ $lokasi }}</td>
                        <td align="right">WAKTU: {{ $laporan->waktu_kejadian ? $laporan->waktu_kejadian->format('d M Y, H:i') : '-' }} WIB</td>
                    </tr>
                </table>

                <div class="status-container">
                    @php
                        $chs = ['AJG' => $laporan->status_ajg, 'BRT' => $laporan->status_brt, 'CCR' => $laporan->status_ccr, 'ECR' => $laporan->status_ecr, 'WKN' => $laporan->status_wkn, 'WKR' => $laporan->status_wkr];
                    @endphp
                    @foreach($chs as $label => $status)
                        @php
                            $status = $status ?? 'Clear';
                            $bgClass = match($status) { 'Clear' => 'bg-clear', 'Blur' => 'bg-blur', 'NA' => 'bg-na', default => 'bg-clear' };
                        @endphp
                        <span class="status-badge {{ $bgClass }}">{{ $label }}: {{ $status }}</span>
                    @endforeach
                    <span class="info-text">Total Snapshot terlampir: {{ $laporan->gambars->count() }} Foto</span>
                </div>

                <div class="image-container">
                    @foreach($laporan->gambars as $gambar)
                        <div class="image-box">
                            @php
                                $path = storage_path('app/public/' . $gambar->path_gambar);
                                $base64 = '';
                                if(file_exists($path)) {
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                }
                            @endphp
                            @if($base64)
                                <img src="{{ $base64 }}" alt="IMG">
                            @else
                                <div style="height: 110px; line-height: 110px; border: 1px dashed #ccc; font-size: 10px; color: #999;">NO IMAGE</div>
                            @endif
                            <div class="image-channel">CH: {{ $gambar->channel }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="text-section">
                    <div class="narrative-box">
                        <strong>NARRATIVE (ASLI):</strong><br>
                        {{ $laporan->isi_laporan ?: '-' }}
                    </div>

                    @if($laporan->catatan_tambahan)
                    <div class="catatan-box">
                        <strong>CATATAN IT (ANALISA):</strong><br>
                        {{ $laporan->catatan_tambahan }}
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endforeach

</body>
</html>
