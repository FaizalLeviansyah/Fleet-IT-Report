<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>IT Fleet Report - {{ $report->vessel->vessel_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        /* KOP SURAT */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-container {
            width: 80px;
            text-align: left;
        }
        .logo-container img {
            width: 100%;
            max-width: 80px;
        }
        .company-info {
            text-align: center;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
            text-transform: uppercase;
        }
        .company-address {
            font-size: 10px;
            margin: 5px 0 2px 0;
        }
        .company-contact {
            font-size: 10px;
            color: #0ea5e9;
            margin: 0;
        }

        /* JUDUL DOKUMEN */
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* TABEL INFO DASAR */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }

        /* TABEL KONTEN (SCOPE) */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table th, .content-table td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            vertical-align: top;
        }
        .content-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: left;
            color: #1e293b;
        }
        .section-title {
            background-color: #e2e8f0;
            font-weight: bold;
            padding: 5px 8px;
            margin-top: 15px;
            margin-bottom: 5px;
            border-left: 4px solid #1e40af;
            font-size: 13px;
        }

        /* SIGNATURE AREA */
        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }
        .signature-table td {
            width: 50%;
        }
        .signature-space {
            height: 80px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-container">
                <img src="{{ public_path('img/Logo_PT_ASM.jpg') }}" alt="Logo Amarin">
            </td>
            <td class="company-info">
                <h1 class="company-name">PT Amarin Ship Management</h1>
                <p class="company-address">Citra Tower jl Benyamin Sueb kav 6a, lt 08 Unit K-L, RT.13/RW.6,<br>Kb. Kosong, Kec. Kemayoran, Kota Jakarta Pusat, DKI Jakarta 10630</p>
                <p class="company-contact">Website: https://amarinshipmanagement.com/</p>
            </td>
        </tr>
    </table>

    <div class="doc-title">
        WEEKLY IT FLEET REPORT
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Vessel Name</td>
            <td>: <strong>{{ $report->vessel->vessel_name }}</strong></td>
            <td class="info-label">Report Date</td>
            <td>: {{ \Carbon\Carbon::parse($report->report_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Company</td>
            <td>: {{ $report->vessel->company_name }}</td>
            <td class="info-label">PIC IT</td>
            <td>: {{ strtoupper($report->vessel->pic_name) }}</td>
        </tr>
    </table>

    <div class="section-title">1. Availability & Network Report</div>
    <table class="content-table">
        <tr>
            <th width="33%">Status Sistem</th>
            <th width="33%">Persentase Uptime</th>
            <th width="34%">SLA Compliance</th>
        </tr>
        <tr>
            <td style="color: {{ $report->vessel_status == 'UP' ? 'green' : 'red' }}; font-weight: bold;">
                {{ $report->vessel_status ?? '-' }}
            </td>
            <td>{{ $report->uptime_percentage ?? '0' }} %</td>
            <td>{{ $report->sla_compliance == 'met' ? 'Terpenuhi' : 'Tidak Terpenuhi' }}</td>
        </tr>
    </table>

    <div class="section-title">2. Incident & Maintenance</div>
    <table class="content-table">
        <tr>
            <th width="50%">Incident / Issue List</th>
            <th width="50%">Root Cause Analysis (RCA)</th>
        </tr>
        <tr>
            <td>{!! nl2br(e($report->incident_list ?? 'Tidak ada insiden.')) !!}</td>
            <td>{!! nl2br(e($report->root_cause ?? '-')) !!}</td>
        </tr>
        <tr>
            <th>Maintenance Type</th>
            <th>Preventive Maintenance / Update</th>
        </tr>
        <tr>
            <td style="text-transform: uppercase;">{{ $report->maintenance_type ?? '-' }}</td>
            <td>{!! nl2br(e($report->preventive_maintenance ?? '-')) !!}</td>
        </tr>
    </table>

    <div class="section-title">3. Performance & Risk Assessment</div>
    <table class="content-table">
        <tr>
            <th width="50%">Performance Trend & Bottleneck</th>
            <th width="50%">Risk Identification & Safety</th>
        </tr>
        <tr>
            <td>{!! nl2br(e($report->performance_trend ?? '-')) !!}</td>
            <td>{!! nl2br(e($report->risk_identification ?? '-')) !!}</td>
        </tr>
    </table>

    <div class="section-title">4. General Operations</div>
    <table class="content-table">
        <tr>
            <th width="50%">Activity Log (Daily)</th>
            <th width="50%">Inventory Tracking</th>
        </tr>
        <tr>
            <td>{!! nl2br(e($report->activity_log ?? '-')) !!}</td>
            <td>{!! nl2br(e($report->inventory_tracking ?? '-')) !!}</td>
        </tr>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                Dibuat Oleh,<br>
                <strong>IT Fleet Support</strong>
                <div class="signature-space"></div>
                <span class="signature-name">{{ Auth::user()->full_name ?? 'IT Department' }}</span><br>
                <span>PT Amarin Ship Management</span>
            </td>
            <td>
                Mengetahui,<br>
                <strong>IT Manager / Superintendent</strong>
                <div class="signature-space"></div>
                <span class="signature-name">Hendry Setio Prakoso</span><br> <span>Head of IT Department</span>
            </td>
        </tr>
    </table>

</body>
</html>
