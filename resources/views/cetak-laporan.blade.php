<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - {{ $laporan->lokasi }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #0056b3; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0056b3; }
        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table th, .info-table td { text-align: left; padding: 8px; border: 1px solid #ddd; }
        .info-table th { background-color: #f4f4f4; width: 30%; }
        .gallery { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .gallery-item { width: 48%; border: 1px solid #ddd; padding: 5px; text-align: center; }
        .gallery-item img { width: 100%; height: auto; display: block; }
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0056b3; color: white; border: none; cursor: pointer;">Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h1>LAPORAN MAINTENANCE CCTV KAPAL</h1>
        <p>Amarin IT Management System</p>
    </div>

    <table class="info-table">
        <tr><th>Nama Kapal</th><td>{{ $laporan->lokasi }}</td></tr>
        <tr><th>Waktu Kejadian</th><td>{{ $laporan->waktu_kejadian }}</td></tr>
        <tr><th>Keterangan</th><td>{{ $laporan->isi_laporan ?? '-' }}</td></tr>
        <tr>
            <th>Status Kamera</th>
            <td>
                CCR: {{ $laporan->status_ccr }} | Front 1: {{ $laporan->status_front1 }} | Front 2: {{ $laporan->status_front2 }} <br>
                Back 1: {{ $laporan->status_back1 }} | Back 2: {{ $laporan->status_back2 }}
            </td>
        </tr>
    </table>

    <h3>Dokumentasi / Snapshot</h3>
    <div class="gallery">
        @forelse($laporan->gambars->where('is_visible', 1) as $gambar)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $gambar->path_gambar) }}" alt="{{ $gambar->channel }}">
                <p>Kamera: {{ $gambar->channel }}</p>
            </div>
        @empty
            <p>Tidak ada snapshot untuk laporan ini.</p>
        @endforelse
    </div>
</body>
</html>
