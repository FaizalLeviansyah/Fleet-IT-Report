<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bulk Export - Laporan CCTV</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 0; }

        /* HEADER SEDERHANA UNTUK BULK EXPORT */
        .header { text-align: center; border-bottom: 3px solid #3b82f6; padding-bottom: 15px; margin-bottom: 30px; margin-top: 10px; }
        .header h1 { margin: 0; font-size: 24px; color: #0f172a; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 12px; font-weight: bold; letter-spacing: 1px; }

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

    <div class="header">
        <h1>LAPORAN CCTV (BULK EXPORT)</h1>
        <p>TANGGAL CETAK: {{ now()->format('d F Y, H:i') }} WIB</p>
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

                            <div class="image-channel">
                                {{ $vesselCustomLabels[$lokasi][$gambar->channel] ?? 'CH: ' . $gambar->channel }}
                            </div>
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
