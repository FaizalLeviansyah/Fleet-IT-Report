<table>
    <tr>
        <td colspan="6" style="background-color: #9bc2e6; font-size: 14px; font-weight: bold; text-align: center; border: 1px solid #000000; height: 30px; vertical-align: middle;">
            Weekly Report IT
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000000;">Nama</td>
        <td style="border: 1px solid #000000;">{{ $report->user->full_name ?? $report->user->name }}</td>
        <td style="border: 1px solid #000000;">Jabatan</td>
        <td style="border: 1px solid #000000;">IT Support</td>
        <td style="border: 1px solid #000000;">Periode</td>
        <td style="border: 1px solid #000000; text-align: center;">{{ \Carbon\Carbon::parse($report->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($report->end_date)->format('d M Y') }}</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>

    <tr>
        <td colspan="6" style="background-color: #9bc2e6; font-weight: bold; text-align: center; border: 1px solid #000000;">ACTUAL PEKERJAAN MINGGU INI</td>
    </tr>
    <tr>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 5px;">No</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Tanggal</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 30px;">Pekerjaan</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 30px;">Hasil Singkat</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 15px;">Status</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000; width: 25px;">Catatan</td>
    </tr>
    @foreach($report->actualTasks as $index => $task)
    <tr>
        <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
        <td style="text-align: center; border: 1px solid #000000;">{{ \Carbon\Carbon::parse($task->task_date)->format('d-M') }}</td>
        <td style="border: 1px solid #000000;">{{ $task->task_name }}</td>
        <td style="border: 1px solid #000000;">{{ $task->result }}</td>
        <td style="border: 1px solid #000000;">{{ $task->status }}</td>
        <td style="border: 1px solid #000000;">{{ $task->notes ?: '-' }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="6"></td>
    </tr>

    <tr>
        <td colspan="6" style="background-color: #9bc2e6; font-weight: bold; text-align: center; border: 1px solid #000000;">PLANNING MINGGU DEPAN</td>
    </tr>
    <tr>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">No</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">Rencana</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">Target</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">Prioritas</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">Deadline</td>
        <td style="background-color: #ddebf7; font-weight: bold; text-align: center; border: 1px solid #000000;">Catatan</td>
    </tr>
    @foreach($report->plannedTasks as $index => $task)
    <tr>
        <td style="text-align: center; border: 1px solid #000000;">{{ $index + 1 }}</td>
        <td style="border: 1px solid #000000;">{{ $task->plan_name }}</td>
        <td style="border: 1px solid #000000;">{{ $task->target }}</td>
        <td style="border: 1px solid #000000;">{{ $task->priority }}</td>
        <td style="text-align: center; border: 1px solid #000000;">{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d-M') : '-' }}</td>
        <td style="border: 1px solid #000000;">{{ $task->notes ?: '-' }}</td>
    </tr>
    @endforeach
</table>
