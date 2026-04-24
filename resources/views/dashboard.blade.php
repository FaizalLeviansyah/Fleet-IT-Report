@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="max-w-7xl mx-auto pb-20">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-black fancy-header tracking-tight">IT Command Center</h1>
            @if($myPendingVessels->count() > 0)
    <div class="mb-8 animate-fade-in-up" style="animation-delay: 0.05s;">
        <div class="w-full relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 border-4 border-amber-200 shadow-xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
            <div class="relative z-10 px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-white text-left">
                    <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm animate-pulse shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg sm:text-xl font-black uppercase tracking-wider drop-shadow-md">TUGAS MINGGU INI BELUM SELESAI, MAS {{ strtoupper(explode(' ', Auth::user()->full_name ?? Auth::user()->name)[0]) }}!</h2>
                        <p class="text-xs sm:text-sm font-bold text-amber-50 mt-0.5 leading-relaxed">
                            Ada <span class="bg-white text-orange-600 px-2 py-0.5 rounded font-black mx-1">{{ $myPendingVessels->count() }} Kapal</span> tanggung jawab Anda yang belum di-submit:
                            <span class="italic text-white">
                                {{ $myPendingVessels->pluck('vessel_name')->take(3)->implode(', ') }}
                                {{ $myPendingVessels->count() > 3 ? '...dll' : '' }}
                            </span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-2 bg-white/20 px-5 py-2.5 rounded-lg text-xs font-black text-white backdrop-blur-sm border border-white/30 hover:bg-white hover:text-orange-600 transition-colors shrink-0">
                    KERJAKAN SEKARANG
                    <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
    @endif
            <p class="text-slate-600 text-xs font-bold uppercase tracking-widest mt-1">Fleet IT Operations & Monitoring</p>
        </div>
        <div class="hidden md:flex items-center gap-3 mt-4 md:mt-0">
            <div class="bg-white border-2 border-slate-300 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 shadow-sm flex items-center gap-2">
                <div class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </div>
                <span>SYSTEM ONLINE</span>
            </div>
            <p class="text-xs text-slate-500 font-bold">Sync: {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="bg-white border-2 border-slate-300 border-l-8 border-l-blue-600 shadow-sm hover:shadow-lg transition-shadow rounded-xl p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div><p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Fleet Monitored</p><h3 class="text-4xl font-black text-slate-900">{{ $totalVessels }}</h3></div>
                <div class="p-3 bg-blue-50 text-blue-700 rounded-lg border-2 border-blue-200"><svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-slate-100 flex items-center text-[10px] font-black text-slate-500">Total Kapal Terdaftar</div>
        </div>
        <div class="bg-white border-2 border-slate-300 border-l-8 border-l-orange-500 shadow-sm hover:shadow-lg transition-shadow rounded-xl p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div><p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Draft Reports</p><h3 class="text-4xl font-black text-slate-900">{{ $draftCount }}</h3></div>
                <div class="p-3 bg-orange-50 text-orange-700 rounded-lg border-2 border-orange-200"><svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-slate-100 flex items-center text-[10px] font-black text-slate-500">Menunggu Review Final</div>
        </div>
        <div class="bg-white border-2 border-slate-300 border-l-8 border-l-red-600 shadow-sm hover:shadow-lg transition-shadow rounded-xl p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div><p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Open Incidents</p><h3 class="text-4xl font-black text-red-600">{{ $incidentCount }}</h3></div>
                <div class="p-3 bg-red-50 text-red-700 rounded-lg border-2 border-red-200"><svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-slate-100 flex items-center text-[10px] font-black text-red-600">Laporan Bermasalah Minggu Ini</div>
        </div>
        <div class="bg-white border-2 border-slate-300 border-l-8 border-l-emerald-500 shadow-sm hover:shadow-lg transition-shadow rounded-xl p-5 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div><p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Avg System Uptime</p><h3 class="text-4xl font-black text-slate-900">{{ $avgUptime }}<span class="text-xl">%</span></h3></div>
                <div class="p-3 bg-emerald-50 text-emerald-700 rounded-lg border-2 border-emerald-200"><svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
            </div>
            <div class="mt-4 pt-3 border-t-2 border-slate-100 flex items-center text-[10px] font-black text-emerald-600">Rata-rata Armada Minggu Ini</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="lg:col-span-2 bg-white border-2 border-slate-300 p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow">
            <div class="mb-4">
                <h3 class="font-black text-lg text-slate-900">System Uptime Trend</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Rata-rata Uptime Armada (4 Minggu Terakhir)</p>
            </div>
            <div id="chart-uptime" class="w-full h-64"></div>
        </div>

        <div class="bg-white border-2 border-slate-300 p-6 rounded-xl shadow-sm hover:shadow-lg transition-shadow flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-slate-100 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none"></div>
            <div>
                <div class="flex justify-between items-center mb-4 relative z-10">
                    <div>
                        <h3 class="font-black text-lg text-slate-900">System Audit Trail</h3>
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Aktivitas Laporan Terbaru</p>
                    </div>
                </div>
                <div class="space-y-4 max-h-56 overflow-y-auto custom-scrollbar pr-2 relative z-10">
                    @forelse($recentActivities as $activity)
                        @php
                            $colorClass = $activity->status == 1 ? 'border-orange-500 text-orange-600' : 'border-emerald-500 text-emerald-600';
                            $actionText = $activity->status == 1 ? 'Draft Auto-Saved' : 'Report Submitted';
                        @endphp
                        <div class="border-l-4 {{ $colorClass }} pl-3 bg-slate-50 hover:bg-slate-100 transition-colors p-2 rounded-r-lg">
                            <p class="text-xs font-black text-slate-800">{{ $actionText }}: {{ $activity->vessel->vessel_name }}</p>
                            <p class="text-[10px] text-slate-500 font-bold">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</p>
                        </div>
                    @empty
                        <div class="text-center p-4">
                            <p class="text-xs font-black text-slate-500 uppercase">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="p-5 border-b-2 border-slate-300 bg-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="font-black text-lg text-slate-900 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500 animate-pulse border-2 border-blue-700"></span>
                    Fleet Report Monitor (Read Only)
                </h3>
            </div>
            <a href="{{ route('reports.index') }}" class="px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 hover:ring-4 hover:ring-blue-200 border-2 border-blue-800 rounded-lg font-black text-xs uppercase tracking-widest transition-all shadow-sm">
                Pergi ke Manajemen Laporan
            </a>
        </div>

        <div class="overflow-x-auto min-h-[300px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-black w-10">No</th>
                        <th class="px-6 py-4 font-black">Detail Kapal</th>
                        <th class="px-6 py-4 font-black text-center">Progress Laporan</th>
                        <th class="px-6 py-4 font-black text-center">PIC IT</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @foreach ($vesselReports as $index => $item)
                    @php
                        $vessel = $item->vessel;
                        $step = $item->status; // 0 (Belum), 1 (Draft), 3 (Final)
                        // Logika step visual: Belum=0, Draft=1, Final=3
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-black text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-black text-blue-800 text-sm group-hover:text-blue-600 transition-colors">{{ $vessel->vessel_name }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">{{ $vessel->company_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center w-full max-w-xs mx-auto opacity-70">
                                <div class="flex items-center"><div class="flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $step >= 1 ? 'bg-orange-500 border-orange-600 text-white' : 'bg-slate-200 border-slate-300 text-slate-400' }} font-black text-[10px] shadow-sm">1</div></div>
                                <div class="w-8 h-1 {{ $step >= 3 ? 'bg-orange-500' : 'bg-slate-200' }}"></div>
                                <div class="flex items-center"><div class="flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $step >= 3 ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-slate-200 border-slate-300 text-slate-400' }} font-black text-[10px] shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $isLevi = str_contains(strtolower($vessel->pic_name), 'levi');
                                $badgeClass = $isLevi ? 'bg-indigo-100 text-indigo-800 border-indigo-300' : 'bg-emerald-100 text-emerald-800 border-emerald-300';
                            @endphp
                            <span class="px-2.5 py-1.5 rounded text-[10px] font-black border-2 shadow-sm {{ $badgeClass }}">
                                {{ strtoupper($vessel->pic_name) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Data dari Controller dilempar ke Javascript
        var chartLabels = {!! json_encode($chartLabels) !!};
        var chartData = {!! json_encode($chartData) !!};

        var optionsUptime = {
            series: [{ name: 'Avg Uptime (%)', data: chartData }],
            chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            colors: ['#3b82f6'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
            dataLabels: { enabled: false }, stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: chartLabels,
                labels: { style: { fontSize: '10px', fontWeight: 800, colors: '#64748b' } },
                axisBorder: { show: false }, axisTicks: { show: false }
            },
            yaxis: { show: true, min: 90, max: 100, labels: { style: { fontSize: '10px', fontWeight: 800, colors: '#64748b' } } },
            grid: { borderColor: '#e2e8f0', strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: true } } },
            legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', fontWeight: 800 },
            tooltip: { theme: 'light', y: { formatter: function (val) { return val + "%" } } }
        };
        new ApexCharts(document.querySelector("#chart-uptime"), optionsUptime).render();
    });
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
