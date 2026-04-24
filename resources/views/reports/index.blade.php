@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Matriks Laporan Tahunan</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Input Laporan Armada Mingguan</p>
            </div>
        </div>

        <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-xl border-2 border-slate-300 shadow-sm">
            <label class="text-xs font-black text-slate-600 uppercase tracking-widest pl-2">TAHUN:</label>
            <input type="number" name="year" value="{{ $selectedYear }}" min="2020" max="2050" class="w-24 text-center rounded-lg border-2 border-slate-300 text-sm font-bold text-slate-800 focus:border-blue-600 focus:ring-0" onchange="this.form.submit()">
        </form>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up relative" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto custom-scrollbar" style="max-height: 65vh; overflow-y: auto;">
            <table class="w-full text-xs text-left whitespace-nowrap border-0 border-collapse">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 sticky top-0 z-30 shadow-sm">
                    <tr>
                        <th class="px-6 py-3 font-black bg-slate-200 border-b-2 border-slate-400 sticky left-0 z-40 w-64 min-w-[250px] shadow-[4px_0_10px_-2px_rgba(0,0,0,0.1)]">Nama Kapal & PIC</th>
                        @foreach($calendar as $monthName => $weeks)
                            <th colspan="{{ count($weeks) }}" class="px-4 py-2 font-black text-center border-b-2 border-l-2 border-slate-400 bg-slate-300">{{ $monthName }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="bg-slate-200 border-b-2 border-slate-400 sticky left-0 z-40 shadow-[4px_0_10px_-2px_rgba(0,0,0,0.1)]"></th>
                        @foreach($calendar as $monthName => $weeks)
                            @foreach($weeks as $weekNum => $targetDate)
                                <th class="px-2 py-2 font-black text-center border-b-2 border-l border-slate-300 bg-slate-100" title="Week {{ $weekNum }} ({{ $targetDate }})">W{{ $loop->iteration }}</th>
                            @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @foreach ($vessels as $vessel)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-4 py-3 bg-white group-hover:bg-slate-50 sticky left-0 z-20 border-r-2 border-slate-300 shadow-[4px_0_10px_-2px_rgba(0,0,0,0.05)]">
                            <div class="font-black text-blue-800 text-sm group-hover:text-blue-600 transition-colors truncate w-56">{{ $vessel->vessel_name }}</div>
                            <div class="flex justify-between items-center mt-1">
                                <span class="text-[9px] text-slate-500 font-bold uppercase truncate">{{ $vessel->company_name }}</span>
                                <span class="text-[9px] font-black bg-slate-200 text-slate-700 px-1.5 rounded">{{ strtoupper($vessel->pic_name) }}</span>
                            </div>
                        </td>

                        @foreach($calendar as $monthName => $weeks)
                            @foreach($weeks as $weekNum => $targetDate)
                                @php
                                    $report = $reportMap[$vessel->id][$weekNum] ?? null;
                                    $status = $report ? $report->status : 0;
                                @endphp
                                <td class="px-1 py-3 border-l border-slate-200 text-center align-middle bg-white group-hover:bg-slate-50">
                                    @if($status == 3)
                                        <button onclick="openPdfPreviewModal({{ $report->id }}, '{{ $vessel->vessel_name }}')" data-modal-target="pdfPreviewModal" data-modal-toggle="pdfPreviewModal" class="w-6 h-6 mx-auto rounded-full bg-emerald-500 hover:bg-emerald-600 border-2 border-emerald-700 shadow-sm hover:scale-125 transition-transform flex justify-center items-center group/btn" title="Final - Klik untuk lihat PDF">
                                            <svg class="w-3 h-3 text-white opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    @elseif($status == 1)
                                        <button onclick="openReportModal({{ $vessel->id }}, '{{ $vessel->vessel_name }}', '{{ $targetDate }}', {{ json_encode($report) }})" data-modal-target="reportModal" data-modal-toggle="reportModal" class="w-6 h-6 mx-auto rounded-full bg-orange-400 hover:bg-orange-500 border-2 border-orange-600 shadow-sm hover:scale-125 transition-transform flex justify-center items-center group/btn" title="Draft - Klik untuk melanjutkan">
                                            <svg class="w-3 h-3 text-white opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    @else
                                        <button onclick="openReportModal({{ $vessel->id }}, '{{ $vessel->vessel_name }}', '{{ $targetDate }}', null)" data-modal-target="reportModal" data-modal-toggle="reportModal" class="w-4 h-4 mx-auto rounded-full bg-slate-200 hover:bg-blue-500 border-2 border-slate-300 hover:border-blue-700 hover:scale-150 transition-all block" title="Kosong - Klik untuk buat laporan W{{ $weekNum }}"></button>
                                    @endif
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-slate-50 p-3 border-t-2 border-slate-300 flex gap-6 items-center text-xs font-bold text-slate-600 uppercase tracking-widest justify-center">
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-emerald-500 border-2 border-emerald-700"></div> Selesai / Final</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-orange-400 border-2 border-orange-600"></div> Draft Tersimpan</div>
            <div class="flex items-center gap-2"><div class="w-4 h-4 rounded-full bg-slate-200 border-2 border-slate-300"></div> Belum Diisi</div>
        </div>
    </div>
</div>

<div id="reportModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-screen max-h-full bg-slate-900/60 backdrop-blur-md transition-opacity">
    <div class="relative p-4 w-full max-w-5xl max-h-full mx-auto">
        <div class="relative bg-white rounded-2xl shadow-2xl flex flex-col w-full max-h-[90vh] border-2 border-slate-300 pointer-events-auto overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 shrink-0 z-20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Form Laporan IT Spesifik</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Input Armada: <span id="modal-vessel-name" class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200">Memuat...</span></p>
                    </div>
                </div>
                <button type="button" id="close-report-modal" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center border border-slate-200 shadow-sm" data-modal-hide="reportModal">
                    <svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="bg-white border-b border-slate-200 px-6 py-4 shrink-0 z-10">
                <ol class="flex items-center w-full text-xs sm:text-sm font-black text-center text-slate-500 sm:text-base">
                    <li id="stepper-1" class="flex md:w-full items-center text-blue-600 sm:after:content-[''] after:w-full after:h-1 after:border-b-2 after:border-blue-200 after:border-solid after:hidden sm:after:inline-block after:mx-2 xl:after:mx-4">
                        <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-slate-200"><span class="w-6 h-6 bg-blue-100 text-blue-600 border-2 border-blue-600 rounded-full flex justify-center items-center mr-2 text-xs">1</span> <span class="hidden sm:inline-block text-[10px] uppercase tracking-widest">Utama & Availability</span></span>
                    </li>
                    <li id="stepper-2" class="flex md:w-full items-center text-slate-400 sm:after:content-[''] after:w-full after:h-1 after:border-b-2 after:border-slate-200 after:border-solid after:hidden sm:after:inline-block after:mx-2 xl:after:mx-4 transition-colors">
                        <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-slate-200"><span class="w-6 h-6 bg-slate-100 text-slate-500 border-2 border-slate-300 rounded-full flex justify-center items-center mr-2 text-xs transition-colors" id="step-circle-2">2</span><span class="hidden sm:inline-block text-[10px] uppercase tracking-widest">Incident & Maint</span></span>
                    </li>
                    <li id="stepper-3" class="flex items-center text-slate-400 transition-colors">
                        <span class="w-6 h-6 bg-slate-100 text-slate-500 border-2 border-slate-300 rounded-full flex justify-center items-center mr-2 text-xs transition-colors" id="step-circle-3">3</span><span class="hidden sm:inline-block text-[10px] uppercase tracking-widest">Lainnya</span>
                    </li>
                </ol>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f7fb] custom-scrollbar min-h-0 relative z-10">
                <form id="reportForm" action="{{ route('reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vessel_id" id="modal-vessel-id">

                    <div id="form-step-1" class="space-y-6 animate-fade-in-up">
                        <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-slate-400"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Informasi Utama</h2></div><div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Periode Laporan</label><input type="date" name="report_date" id="modal-report-date" required class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></div></div></div>
                        <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-blue-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">1. Availability Report</h2></div><div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4"><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Status</label><select name="vessel_status" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="UP">UP</option><option value="DOWN">DOWN</option></select></div><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Uptime (%)</label><input type="number" step="0.01" name="uptime_percentage" placeholder="99.5" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></div><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">SLA</label><select name="sla_compliance" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="met">Terpenuhi</option><option value="not_met">Tidak Terpenuhi</option></select></div></div></div>
                    </div>

                    <div id="form-step-2" class="hidden space-y-6 animate-fade-in-up">
                        <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-red-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">2. Incident / Issue</h2></div><div class="p-4 space-y-4"><textarea name="incident_list" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="Masalah yang terjadi..."></textarea><textarea name="root_cause" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="RCA..."></textarea></div></div>
                        <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-emerald-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">3. Maintenance Report</h2></div><div class="p-4 space-y-4"><select name="maintenance_type" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="planned">Planned</option><option value="unplanned">Unplanned</option></select><textarea name="preventive_maintenance" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="Preventive Maintenance..."></textarea></div></div>
                    </div>

                    <div id="form-step-3" class="hidden space-y-6 animate-fade-in-up">
                        <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-indigo-500"><div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">4. Performance</label><textarea name="performance_trend" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">5. Risk & Safety</label><textarea name="risk_identification" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">6. Activity Log</label><textarea name="activity_log" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">7. Inventory</label><textarea name="inventory_tracking" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div></div></div>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between p-4 md:p-5 border-t-2 border-slate-200 bg-white rounded-b-xl shrink-0 z-20 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
                <button type="button" id="btn-prev" onclick="changeStep(-1)" class="hidden px-6 py-3 bg-slate-100 text-slate-600 border-2 border-slate-300 hover:bg-slate-200 hover:ring-4 hover:ring-slate-100 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm"><svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>Kembali</button>
                <div id="spacer-prev" class="w-10"></div>
                <div class="flex gap-3">
                    <button type="submit" form="reportForm" name="action_type" value="draft" class="px-6 py-3 bg-orange-50 text-orange-700 border-2 border-orange-300 hover:bg-orange-100 hover:ring-4 hover:ring-orange-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">Simpan Draft</button>
                    <button type="button" id="btn-next" onclick="changeStep(1)" class="px-6 py-3 bg-blue-600 text-white border-2 border-blue-800 hover:bg-blue-700 hover:ring-4 hover:ring-blue-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">Lanjut<svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg></button>
                    <button type="submit" id="btn-submit" form="reportForm" name="action_type" value="final" class="hidden flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white border-2 border-emerald-600 hover:bg-emerald-600 hover:ring-4 hover:ring-emerald-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>SUBMIT FINAL</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pdfPreviewModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-screen max-h-full bg-slate-900/60 backdrop-blur-md transition-opacity">
    <div class="relative p-4 w-full max-w-5xl h-full flex flex-col justify-center items-center">
        <div class="relative w-full bg-white rounded-2xl shadow-2xl flex flex-col border-2 border-slate-300 h-[90vh] overflow-hidden">
            <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 shrink-0 z-20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 text-red-600 rounded-lg border border-red-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg></div>
                    <div><h3 class="text-lg font-black text-slate-900">Preview Laporan PDF</h3><p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Armada: <span id="modal-pdf-vessel-name" class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200">Memuat...</span></p></div>
                </div>
                <button type="button" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center border border-slate-200 shadow-sm" data-modal-hide="pdfPreviewModal"><svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="flex-1 w-full bg-slate-200 relative z-10 overflow-hidden">
                <iframe id="pdf-iframe" src="" class="w-full h-full border-0" title="PDF Preview"></iframe>
            </div>
            <div class="flex items-center justify-end gap-3 p-4 md:p-5 border-t-2 border-slate-200 bg-white rounded-b-xl shrink-0 z-20 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
                <button data-modal-hide="pdfPreviewModal" class="px-6 py-2.5 bg-slate-100 text-slate-600 border-2 border-slate-300 hover:bg-slate-200 rounded-lg font-black text-xs uppercase tracking-widest transition-all">Tutup</button>
                <a id="modal-download-btn" href="#" class="flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white border-2 border-red-800 hover:bg-red-700 hover:ring-4 hover:ring-red-200 hover:scale-105 rounded-lg font-black text-xs uppercase tracking-widest transition-all shadow-md"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>UNDUH FILE PDF</a>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    document.addEventListener("DOMContentLoaded", function() {
        document.body.appendChild(document.getElementById('reportModal'));
        document.body.appendChild(document.getElementById('pdfPreviewModal'));
    });

    function changeStep(direction) {
        document.getElementById(`form-step-${currentStep}`).classList.add('hidden');
        currentStep += direction;
        document.getElementById(`form-step-${currentStep}`).classList.remove('hidden');

        for(let i=1; i<=totalSteps; i++) {
            let stepLi = document.getElementById(`stepper-${i}`);
            let stepCircle = document.getElementById(`step-circle-${i}`);
            if(i <= currentStep) {
                stepLi.classList.add('text-blue-600'); stepLi.classList.remove('text-slate-400');
                if(stepCircle) { stepCircle.classList.add('bg-blue-100', 'text-blue-600', 'border-blue-600'); stepCircle.classList.remove('bg-slate-100', 'text-slate-500', 'border-slate-300'); }
                if(i > 1) document.getElementById(`stepper-${i-1}`).classList.replace('after:border-slate-200', 'after:border-blue-200');
            } else {
                stepLi.classList.remove('text-blue-600'); stepLi.classList.add('text-slate-400');
                if(stepCircle) { stepCircle.classList.remove('bg-blue-100', 'text-blue-600', 'border-blue-600'); stepCircle.classList.add('bg-slate-100', 'text-slate-500', 'border-slate-300'); }
                if(i > 1) document.getElementById(`stepper-${i-1}`).classList.replace('after:border-blue-200', 'after:border-slate-200');
            }
        }

        document.getElementById('btn-prev').classList.toggle('hidden', currentStep === 1);
        document.getElementById('spacer-prev').classList.toggle('hidden', currentStep !== 1);

        if (currentStep === totalSteps) {
            document.getElementById('btn-next').classList.add('hidden');
            document.getElementById('btn-submit').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('flex');
        } else {
            document.getElementById('btn-next').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('hidden');
            document.getElementById('btn-submit').classList.remove('flex');
        }
    }

    function openReportModal(vesselId, vesselName, targetDate, reportData) {
        const today = new Date().getDay();

        if (!reportData && (today >= 1 && today <= 3)) {
            Swal.fire({
                title: 'Belum Waktunya!',
                text: "Laporan Mingguan idealnya diisi pada hari Kamis/Jumat untuk merangkum operasional 1 minggu. Yakin ingin mulai mencicil draf sekarang?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Cicil Draf',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'border-2 border-slate-300 rounded-2xl shadow-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    lanjutBukaModal(vesselId, vesselName, targetDate, reportData);
                } else {
                    // TUTUP PAKSA MODAL JIKA BATAL DIKLIK!
                    document.getElementById('close-report-modal').click();
                }
            });
            return;
        }

        lanjutBukaModal(vesselId, vesselName, targetDate, reportData);
    }

    function lanjutBukaModal(vesselId, vesselName, targetDate, reportData) {
        document.getElementById('reportForm').reset();

        currentStep = 1;
        document.getElementById('form-step-1').classList.remove('hidden');
        document.getElementById('form-step-2').classList.add('hidden');
        document.getElementById('form-step-3').classList.add('hidden');
        changeStep(0);

        document.getElementById('modal-vessel-id').value = vesselId;
        document.getElementById('modal-vessel-name').innerText = vesselName;
        document.getElementById('modal-report-date').value = targetDate;

        if(reportData) {
            if(reportData.report_date) document.getElementById('modal-report-date').value = reportData.report_date.substring(0, 10);
            if(reportData.vessel_status) document.querySelector('[name="vessel_status"]').value = reportData.vessel_status;
            if(reportData.uptime_percentage) document.querySelector('[name="uptime_percentage"]').value = reportData.uptime_percentage;
            if(reportData.sla_compliance) document.querySelector('[name="sla_compliance"]').value = reportData.sla_compliance;
            if(reportData.maintenance_type) document.querySelector('[name="maintenance_type"]').value = reportData.maintenance_type;

            const textareas = ['incident_list', 'root_cause', 'preventive_maintenance', 'performance_trend', 'risk_identification', 'activity_log', 'inventory_tracking'];
            textareas.forEach(field => {
                if(reportData[field]) { document.querySelector(`[name="${field}"]`).value = reportData[field]; }
            });
        }
    }

    function openPdfPreviewModal(reportId, vesselName) {
        document.getElementById('modal-pdf-vessel-name').innerText = vesselName;
        const pdfUrl = `/reports/${reportId}/pdf`;
        document.getElementById('pdf-iframe').src = pdfUrl;
        document.getElementById('modal-download-btn').href = pdfUrl + '?download=true';
    }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    div[modal-backdrop] { display: none !important; }
</style>
@endsection
