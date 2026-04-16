@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="flex items-center gap-4 mb-6 animate-fade-in-up">
        <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Update Manajemen Laporan</h1>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Sistem Input Laporan Armada Mingguan</p>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto min-h-[500px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-black w-10">No</th>
                        <th class="px-6 py-4 font-black">Detail Kapal</th>
                        <th class="px-6 py-4 font-black text-center">Status Pengerjaan</th>
                        <th class="px-6 py-4 font-black text-right">Aksi Input Laporan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @foreach ($vessels as $index => $vessel)
                    @php $step = ($index % 3) + 1; @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-black text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-black text-blue-800 text-sm group-hover:text-blue-600 transition-colors">{{ $vessel->vessel_name }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">{{ $vessel->company_name }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-center font-black uppercase {{ $step == 1 ? 'text-orange-600' : ($step == 2 ? 'text-blue-600' : 'text-emerald-600') }}">
                                {{ $step == 1 ? 'DRAFT TERSIMPAN' : ($step == 2 ? 'SEDANG DIREVIEW' : 'SELESAI (COMPLETED)') }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            @if($step == 3)
                                <button class="inline-flex items-center justify-center px-5 py-2.5 text-[10px] font-black text-slate-700 bg-white border-2 border-slate-300 hover:bg-slate-50 hover:ring-4 hover:ring-slate-200 rounded-lg transition-all shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    UNDUH PDF
                                </button>
                            @else
                                <button onclick="openReportModal({{ $vessel->id }}, '{{ $vessel->vessel_name }}')" data-modal-target="reportModal" data-modal-toggle="reportModal" class="inline-flex items-center justify-center px-5 py-2.5 text-[10px] font-black text-white {{ $step == 1 ? 'bg-orange-500 border-orange-600 hover:bg-orange-600 hover:ring-orange-200' : 'bg-blue-600 border-blue-800 hover:bg-blue-700 hover:ring-blue-300' }} border-2 hover:ring-4 hover:scale-105 rounded-lg transition-all shadow-md cursor-pointer">
                                    {{ $step == 1 ? 'LANJUTKAN DRAFT' : 'BUAT LAPORAN' }}
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="reportModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[100] flex items-center justify-center w-full h-full bg-slate-900/60 backdrop-blur-sm p-4 sm:p-6">

    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl flex flex-col h-[90vh] border-2 border-slate-300">

        <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 rounded-t-2xl shrink-0 z-20">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Form Laporan IT Spesifik</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Input Armada: <span id="modal-vessel-name" class="text-blue-700 bg-blue-100 px-2 py-0.5 rounded border border-blue-200">Memuat...</span></p>
                </div>
            </div>
            <button type="button" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center border border-slate-200 shadow-sm" data-modal-hide="reportModal">
                <svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f7fb] custom-scrollbar relative z-10">
            <form id="reportForm" action="{{ route('reports.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="vessel_id" id="modal-vessel-id">

                <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-blue-500 hover:shadow-md transition-shadow">
                    <div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">1. Availability Report</h2></div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Status</label><select name="vessel_status" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"><option value="UP">UP</option><option value="DOWN">DOWN</option></select></div>
                        <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Uptime (%)</label><input type="number" step="0.01" name="uptime_percentage" placeholder="99.5" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></div>
                        <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">SLA</label><select name="sla_compliance" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"><option value="met">Terpenuhi</option><option value="not_met">Tidak Terpenuhi</option></select></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-red-500 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">2. Incident / Issue</h2></div>
                        <div class="p-4 space-y-4">
                            <textarea name="incident_list" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all" placeholder="Masalah yang terjadi..."></textarea>
                            <textarea name="root_cause" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all" placeholder="RCA..."></textarea>
                        </div>
                    </div>
                    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden border-l-8 border-l-emerald-500 hover:shadow-md transition-shadow">
                        <div class="p-3 bg-slate-50 border-b border-slate-200"><h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">3. Maintenance Report</h2></div>
                        <div class="p-4 space-y-4">
                            <select name="maintenance_type" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"><option value="planned">Planned</option><option value="unplanned">Unplanned</option></select>
                            <textarea name="preventive_maintenance" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all" placeholder="Preventive Maintenance..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">4. Performance</label><textarea name="performance_trend" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></textarea></div>
                        <div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">5. Risk & Safety</label><textarea name="risk_identification" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></textarea></div>
                        <div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">6. Activity Log</label><textarea name="activity_log" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></textarea></div>
                        <div><label class="block mb-2 text-xs font-black text-slate-800 uppercase tracking-widest">7. Inventory</label><textarea name="inventory_tracking" rows="2" class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold placeholder-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition-all"></textarea></div>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-end gap-3 p-4 md:p-5 border-t-2 border-slate-200 bg-white rounded-b-2xl shrink-0 z-20 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
            <button type="submit" form="reportForm" name="action_type" value="draft" class="px-6 py-3 bg-orange-50 text-orange-700 border-2 border-orange-300 hover:bg-orange-100 hover:ring-4 hover:ring-orange-200 hover:scale-105 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">
                Simpan Draft
            </button>
            <button type="submit" form="reportForm" name="action_type" value="final" class="flex items-center gap-2 px-6 py-3 bg-emerald-500 text-white border-2 border-emerald-600 hover:bg-emerald-600 hover:ring-4 hover:ring-emerald-200 hover:scale-105 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                SUBMIT FINAL
            </button>
        </div>
    </div>
</div>

<script>
    function openReportModal(vesselId, vesselName) {
        document.getElementById('modal-vessel-id').value = vesselId;
        document.getElementById('modal-vessel-name').innerText = vesselName;
    }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
