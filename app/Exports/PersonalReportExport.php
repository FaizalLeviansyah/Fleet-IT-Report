<?php

namespace App\Exports;

use App\Models\PersonalItReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PersonalReportExport implements FromView, ShouldAutoSize
{
    protected $reportId;

    public function __construct($reportId)
    {
        $this->reportId = $reportId;
    }

    public function view(): View
    {
        $report = PersonalItReport::with(['actualTasks', 'plannedTasks', 'user'])->findOrFail($this->reportId);

        return view('personal_reports.export_excel', [
            'report' => $report
        ]);
    }
}
