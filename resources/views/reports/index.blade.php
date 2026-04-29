@extends('layouts.app')

@section('content')
<div class="max-w-full mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Input Laporan Armada</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Matriks Laporan Mingguan</p>
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
                                        <button onclick="openPdfPreviewModal({{ $report->id }}, '{{ $vessel->vessel_name }}')" class="w-6 h-6 mx-auto rounded-full bg-emerald-500 hover:bg-emerald-600 border-2 border-emerald-700 shadow-sm hover:scale-125 transition-transform flex justify-center items-center group/btn" title="Final - Klik untuk lihat PDF">
                                            <svg class="w-3 h-3 text-white opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    @elseif($status == 1)
                                        <button onclick="openReportModal({{ $vessel->id }}, '{{ $vessel->vessel_name }}', '{{ $targetDate }}', {{ json_encode($report) }})" class="w-6 h-6 mx-auto rounded-full bg-orange-400 hover:bg-orange-500 border-2 border-orange-600 shadow-sm hover:scale-125 transition-transform flex justify-center items-center group/btn" title="Draft - Klik untuk melanjutkan">
                                            <svg class="w-3 h-3 text-white opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    @else
                                        <button onclick="openReportModal({{ $vessel->id }}, '{{ $vessel->vessel_name }}', '{{ $targetDate }}', null)" class="w-4 h-4 mx-auto rounded-full bg-slate-200 hover:bg-blue-500 border-2 border-slate-300 hover:border-blue-700 hover:scale-150 transition-all block" title="Kosong - Klik untuk buat laporan W{{ $weekNum }}"></button>
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

<div id="reportModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="relative w-full max-w-5xl max-h-[95vh] mx-auto flex flex-col bg-white rounded-2xl shadow-2xl border-2 border-slate-300 animate-fade-in-up">

        <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 rounded-t-2xl shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Form Laporan IT Spesifik</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Input Armada: <span id="modal-vessel-name" class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200">Memuat...</span></p>
                </div>
            </div>
            <button type="button" onclick="closeReportModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center border border-slate-200 shadow-sm">
                <svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="bg-white border-b border-slate-200 px-6 py-4 shrink-0">
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

        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f7fb] custom-scrollbar">
            <form id="reportForm" action="{{ route('reports.store') }}" method="POST">
                @csrf
                <input type="hidden" name="vessel_id" id="modal-vessel-id">
                <input type="hidden" name="late_remark" id="modal-late-remark">

                <div id="late-remark-alert" class="hidden mb-6 p-4 rounded-xl border-2 border-red-300 bg-red-50">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">Laporan Terlambat (Late Submission)</h3>
                            <p class="text-xs font-bold text-red-600 mt-1">Remark Audit: <span id="late-remark-text" class="text-slate-700 italic"></span></p>
                        </div>
                    </div>
                </div>

                <div id="form-step-1" class="space-y-6">
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-slate-400"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Informasi Utama</h2></div><div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4"><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Periode Laporan</label><input type="date" name="report_date" id="modal-report-date" required class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold bg-slate-100 text-slate-500 cursor-not-allowed pointer-events-none" readonly></div></div></div>
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-blue-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">1. Availability Report</h2></div><div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4"><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Status <span class="text-red-500">*</span></label><select name="vessel_status" id="input_vessel_status" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="UP">UP</option><option value="DOWN">DOWN</option></select></div><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Uptime (%) <span class="text-red-500">*</span></label><input type="number" step="0.01" name="uptime_percentage" id="input_uptime" placeholder="Contoh: 100" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></div><div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">SLA <span class="text-red-500">*</span></label><select name="sla_compliance" id="input_sla" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="met">Terpenuhi</option><option value="not_met">Tidak Terpenuhi</option></select></div></div></div>
                </div>

                <div id="form-step-2" class="hidden space-y-6">
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-red-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">2. Incident / Issue</h2></div><div class="p-4 space-y-4"><textarea name="incident_list" id="input_incident" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="Masalah yang terjadi... (Kosongkan jika tidak ada)"></textarea><textarea name="root_cause" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="RCA... (Kosongkan jika tidak ada)"></textarea></div></div>
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-emerald-500"><div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">3. Maintenance Report</h2></div><div class="p-4 space-y-4"><select name="maintenance_type" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"><option value="planned">Planned</option><option value="unplanned">Unplanned</option></select><textarea name="preventive_maintenance" id="input_maintenance" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100" placeholder="Preventive Maintenance... (Wajib diisi minimal keterangan 'Pengecekan Rutin')"></textarea></div></div>
                </div>

                <div id="form-step-3" class="hidden space-y-6">
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-indigo-500"><div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-5"><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">4. Performance</label><textarea name="performance_trend" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">5. Risk & Safety</label><textarea name="risk_identification" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">6. Activity Log</label><textarea name="activity_log" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div><div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">7. Inventory</label><textarea name="inventory_tracking" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100"></textarea></div></div></div>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-between p-4 md:p-5 border-t-2 border-slate-200 bg-white rounded-b-2xl shrink-0 z-20">
            <button type="button" id="btn-prev" onclick="changeStep(-1)" class="hidden px-6 py-3 bg-slate-100 text-slate-600 border-2 border-slate-300 hover:bg-slate-200 hover:ring-4 hover:ring-slate-100 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm"><svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>Kembali</button>
            <div id="spacer-prev" class="w-10"></div>
            <div class="flex gap-3">
                <button type="button" onclick="submitDraft()" class="px-6 py-3 bg-orange-50 text-orange-700 border-2 border-orange-300 hover:bg-orange-100 hover:ring-4 hover:ring-orange-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">Simpan Draft</button>
                <button type="button" id="btn-next" onclick="changeStep(1)" class="px-6 py-3 bg-blue-600 text-white border-2 border-blue-800 hover:bg-blue-700 hover:ring-4 hover:ring-blue-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">Lanjut<svg class="w-4 h-4 inline-block ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg></button>
                <button type="button" id="btn-submit" onclick="submitFinal()" class="hidden flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white border-2 border-emerald-600 hover:bg-emerald-600 hover:ring-4 hover:ring-emerald-200 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>SUBMIT FINAL</button>
            </div>
        </div>
    </div>
</div>

<div id="pdfPreviewModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="relative w-full max-w-5xl h-[95vh] mx-auto flex flex-col bg-white rounded-2xl shadow-2xl border-2 border-slate-300 animate-fade-in-up">
        <div class="flex items-center justify-between p-4 border-b-2 border-slate-200 bg-slate-50 shrink-0 rounded-t-2xl">
            <h3 class="text-lg font-black text-slate-900">Preview PDF: <span id="modal-pdf-vessel-name" class="text-blue-600"></span></h3>
            <button type="button" onclick="closePdfModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 border border-slate-200 rounded-lg text-sm w-8 h-8 flex justify-center items-center"><svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="flex-1 w-full bg-slate-200 relative z-10 overflow-hidden">
            <iframe id="pdf-iframe" src="" class="w-full h-full border-0"></iframe>
        </div>
        <div class="flex items-center justify-end gap-3 p-4 border-t-2 border-slate-200 bg-white rounded-b-2xl shrink-0 z-20">
            <button onclick="closePdfModal()" class="px-6 py-2.5 bg-slate-100 text-slate-600 border-2 border-slate-300 hover:bg-slate-200 rounded-lg font-black text-xs uppercase">Tutup</button>
            <a id="modal-download-btn" href="#" class="px-6 py-2.5 bg-red-600 text-white border-2 border-red-800 hover:bg-red-700 rounded-lg font-black text-xs uppercase shadow-md">Unduh PDF</a>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    // FUNGSI SMART VALIDATOR
    function validateStep(step) {
        if (step === 1) {
            const uptime = document.getElementById('input_uptime').value;
            if (!uptime || uptime === '') {
                Swal.fire({ title: 'Data Belum Lengkap!', text: 'Kolom Uptime (%) wajib diisi sebelum melanjutkan.', icon: 'warning', confirmButtonColor: '#3b82f6' });
                return false;
            }
        }
        if (step === 2) {
            const maintenance = document.getElementById('input_maintenance').value;
            const status = document.getElementById('input_vessel_status').value;
            const incident = document.getElementById('input_incident').value;

            // Jika status DOWN, wajib isi incident
            if (status === 'DOWN' && (!incident || incident.trim() === '')) {
                Swal.fire({ title: 'Data Belum Lengkap!', text: 'Karena status armada DOWN, kolom Incident/Issue wajib diisi!', icon: 'warning', confirmButtonColor: '#ef4444' });
                return false;
            }
            // Maintenance wajib diisi
            if (!maintenance || maintenance.trim() === '') {
                Swal.fire({ title: 'Data Belum Lengkap!', text: 'Kolom Preventive Maintenance wajib diisi. (Isi dengan "Pengecekan Rutin" jika tidak ada tindakan khusus)', icon: 'warning', confirmButtonColor: '#3b82f6' });
                return false;
            }
        }
        return true;
    }

    // INTERCEPT SUBMIT BUTTONS
    function submitDraft() {
        // Draft boleh disimpan walau kosong, jadi tidak perlu divalidasi ketat
        const form = document.getElementById('reportForm');
        const inputAction = document.createElement('input');
        inputAction.type = 'hidden'; inputAction.name = 'action_type'; inputAction.value = 'draft';
        form.appendChild(inputAction);
        form.submit();
    }

    function submitFinal() {
        // Submit final harus melewati validasi langkah terakhir
        if(validateStep(1) && validateStep(2)) {
            const form = document.getElementById('reportForm');
            const inputAction = document.createElement('input');
            inputAction.type = 'hidden'; inputAction.name = 'action_type'; inputAction.value = 'final';
            form.appendChild(inputAction);
            form.submit();
        }
    }

    function setStep(stepNum) {
        document.getElementById('form-step-1').classList.add('hidden');
        document.getElementById('form-step-2').classList.add('hidden');
        document.getElementById('form-step-3').classList.add('hidden');

        currentStep = stepNum;
        if(currentStep < 1) currentStep = 1;
        if(currentStep > 3) currentStep = 3;

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

    function changeStep(direction) {
        // Cegah pindah langkah jika validasi gagal
        if (direction === 1 && !validateStep(currentStep)) {
            return;
        }
        setStep(currentStep + direction);
    }

    function openReportModal(vesselId, vesselName, targetDate, reportData) {
        const currentWeekStart = "{{ now()->startOfWeek()->format('Y-m-d') }}";
        const currentWeekEnd = "{{ now()->endOfWeek(\Carbon\Carbon::FRIDAY)->format('Y-m-d') }}";

        if (!reportData && targetDate > currentWeekEnd) {
            Swal.fire({ title: 'Akses Ditolak!', text: 'Anda tidak dapat membuat laporan untuk minggu yang belum terjadi.', icon: 'error', confirmButtonColor: '#dc2626' });
            return;
        }

        if (!reportData && targetDate < currentWeekStart) {
            Swal.fire({
                title: 'Laporan Terlambat!',
                text: 'Periode minggu ini sudah terlewat. Wajib mengisi alasan (remark).',
                input: 'textarea',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Lanjut',
                preConfirm: (text) => { if (!text) Swal.showValidationMessage('Alasan wajib diisi!'); return text; }
            }).then((result) => {
                if (result.isConfirmed) {
                    const lateInput = document.getElementById('modal-late-remark');
                    const lateAlert = document.getElementById('late-remark-alert');
                    if(lateInput) lateInput.value = result.value;
                    if(lateAlert) {
                        lateAlert.classList.remove('hidden');
                        document.getElementById('late-remark-text').innerText = `"${result.value}"`;
                    }
                    lanjutBukaModal(vesselId, vesselName, targetDate, reportData);
                }
            });
            return;
        }
        lanjutBukaModal(vesselId, vesselName, targetDate, reportData);
    }

    function lanjutBukaModal(vesselId, vesselName, targetDate, reportData) {
        document.getElementById('reportModal').classList.remove('hidden');
        document.getElementById('reportModal').classList.add('flex');

        document.getElementById('reportForm').reset();

        if (!reportData && targetDate >= "{{ now()->startOfWeek()->format('Y-m-d') }}") {
            const lateAlert = document.getElementById('late-remark-alert');
            const lateInput = document.getElementById('modal-late-remark');
            if(lateAlert) lateAlert.classList.add('hidden');
            if(lateInput) lateInput.value = '';
        }

        setStep(1);

        document.getElementById('modal-vessel-id').value = vesselId;
        document.getElementById('modal-vessel-name').innerText = vesselName;
        document.getElementById('modal-report-date').value = targetDate;

        if(reportData) {
            if(reportData.late_remark) {
                const lateAlert = document.getElementById('late-remark-alert');
                if(lateAlert) {
                    lateAlert.classList.remove('hidden');
                    document.getElementById('late-remark-text').innerText = `"${reportData.late_remark}"`;
                    document.getElementById('modal-late-remark').value = reportData.late_remark;
                }
            }

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

    function closeReportModal() {
        document.getElementById('reportModal').classList.add('hidden');
        document.getElementById('reportModal').classList.remove('flex');
    }

    function openPdfPreviewModal(reportId, vesselName) {
        document.getElementById('pdfPreviewModal').classList.remove('hidden');
        document.getElementById('pdfPreviewModal').classList.add('flex');
        document.getElementById('modal-pdf-vessel-name').innerText = vesselName;
        const pdfUrl = `/reports/${reportId}/pdf`;
        document.getElementById('pdf-iframe').src = pdfUrl;
        document.getElementById('modal-download-btn').href = pdfUrl + '?download=true';
    }

    function closePdfModal() {
        document.getElementById('pdfPreviewModal').classList.add('hidden');
        document.getElementById('pdfPreviewModal').classList.remove('flex');
        document.getElementById('pdf-iframe').src = '';
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
