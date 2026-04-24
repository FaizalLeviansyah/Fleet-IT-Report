@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-6 animate-fade-in-up">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Riwayat Laporan</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Arsip Laporan IT Armada yang Telah Selesai</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl border-2 border-slate-300 shadow-sm mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('reports.history') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" class="w-full pl-10 pr-4 py-2.5 rounded-lg border-2 border-slate-300 text-sm font-bold text-slate-800 focus:border-blue-600 focus:ring-0 transition-all" placeholder="Cari nama kapal atau tahun (ex: 2026)...">
            </div>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-black text-xs uppercase tracking-widest rounded-lg border-2 border-blue-800 hover:bg-blue-700 shadow-sm transition-all">
                Cari Arsip
            </button>
            @if($search)
            <a href="{{ route('reports.history') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-black text-xs uppercase tracking-widest rounded-lg border-2 border-slate-300 hover:bg-slate-200 text-center transition-all">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="overflow-x-auto min-h-[400px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-black w-10">No</th>
                        <th class="px-6 py-4 font-black">Tanggal Laporan</th>
                        <th class="px-6 py-4 font-black">Detail Kapal</th>
                        <th class="px-6 py-4 font-black text-center">Status Uptime</th>
                        <th class="px-6 py-4 font-black text-right">Dokumen PDF</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @forelse ($historyReports as $index => $report)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-black text-slate-900">{{ $historyReports->firstItem() + $index }}</td>

                        <td class="px-6 py-4">
                            <div class="font-black text-slate-800 text-sm">{{ \Carbon\Carbon::parse($report->report_date)->format('d M Y') }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">{{ \Carbon\Carbon::parse($report->report_date)->diffForHumans() }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="font-black text-blue-800 text-sm group-hover:text-blue-600 transition-colors">{{ $report->vessel->vessel_name }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">{{ $report->vessel->company_name }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center justify-center">
                                <span class="px-2.5 py-1 rounded text-[10px] font-black border-2 shadow-sm {{ $report->vessel_status == 'UP' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-red-100 text-red-800 border-red-300' }}">
                                    {{ $report->vessel_status ?? 'UNKNOWN' }}
                                </span>
                                <span class="text-[9px] font-bold text-slate-500 mt-1">{{ $report->uptime_percentage ?? 0 }}% SLA</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('reports.pdf', $report->id) }}" class="inline-flex items-center justify-center px-5 py-2.5 text-[10px] font-black text-slate-700 bg-white border-2 border-slate-300 hover:bg-slate-50 hover:ring-4 hover:ring-slate-200 rounded-lg transition-all shadow-sm">
                                <svg class="w-3 h-3 mr-1 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                UNDUH PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-100 text-slate-500 border-2 border-slate-300 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-sm font-black text-slate-800">Belum Ada Arsip</p>
                            <p class="text-xs font-bold text-slate-500 mt-1">Laporan yang sudah berstatus FINAL akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historyReports->hasPages())
        <div class="p-4 border-t-2 border-slate-200 bg-slate-50">
            {{ $historyReports->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
