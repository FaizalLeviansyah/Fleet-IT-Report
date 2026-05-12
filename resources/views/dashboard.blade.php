@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="max-w-[1600px] mx-auto pb-20">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-black fancy-header tracking-tight">IT Command Center</h1>
            <p class="text-slate-600 text-[11px] font-bold uppercase tracking-widest mt-1">Universal Fleet Management (ITSM & Reporting)</p>
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
                        <h2 class="text-lg sm:text-xl font-black uppercase tracking-wider drop-shadow-md">TUGAS MINGGU INI BELUM SELESAI, MAS {{ strtoupper(explode(' ', Auth::user()->full_name ?? Auth::user()->name ?? 'IT')[0]) }}!</h2>
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

    <div class="mb-4 mt-8 flex items-center gap-3 border-b-2 border-slate-200 pb-2">
        <i class="fa-solid fa-satellite-dish text-blue-600 text-xl"></i>
        <h2 class="text-lg font-black text-slate-800 tracking-widest uppercase">Live Telemetry & Operations</h2>
        <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[9px] font-black rounded uppercase ml-2 animate-pulse">Real-Time</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="bg-white rounded-xl border-2 border-slate-200 p-5 shadow-sm relative overflow-hidden group hover:border-emerald-300 transition-colors">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity"><i class="fa-solid fa-heart-pulse text-9xl text-emerald-500"></i></div>
            <div class="flex justify-between items-start mb-2 relative z-10">
                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase rounded tracking-widest">Live Uptime</span>
                <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i class="fa-solid fa-heart-pulse"></i></div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-slate-800">{{ number_format($fleetHealth, 1) }}<span class="text-lg text-slate-400">%</span></h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Fleet Health Score</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border-2 border-slate-200 p-5 shadow-sm relative overflow-hidden group hover:border-amber-300 transition-colors">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity"><i class="fa-solid fa-ticket text-9xl text-amber-500"></i></div>
            <div class="flex justify-between items-start mb-2 relative z-10">
                <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded tracking-widest">ITSM Tickets</span>
                <div class="p-2 bg-amber-50 text-amber-600 rounded-lg"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-slate-800">{{ $activeTickets->count() }}</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Active Incidents</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border-2 border-slate-200 p-5 shadow-sm relative overflow-hidden group hover:border-blue-300 transition-colors">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity"><i class="fa-solid fa-network-wired text-9xl text-blue-500"></i></div>
            <div class="flex justify-between items-start mb-2 relative z-10">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[9px] font-black uppercase rounded tracking-widest">Sentinel Online</span>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="fa-solid fa-check-double"></i></div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-slate-800">{{ $onlineAssets }}<span class="text-sm text-slate-400 font-bold ml-1">/ {{ $totalAssets }}</span></h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Connected Devices</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border-2 border-slate-200 p-5 shadow-sm relative overflow-hidden group hover:border-red-300 transition-colors">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:opacity-10 transition-opacity"><i class="fa-solid fa-video-slash text-9xl text-red-500"></i></div>
            <div class="flex justify-between items-start mb-2 relative z-10">
                <span class="px-2 py-1 bg-red-100 text-red-700 text-[9px] font-black uppercase rounded tracking-widest">Lost Connection</span>
                <div class="p-2 bg-red-50 text-red-600 rounded-lg"><i class="fa-solid fa-plug-circle-xmark"></i></div>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-red-600">{{ $offlineAssets }}</h3>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Assets & CCTV Offline</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-12 animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="bg-white border-2 border-slate-200 rounded-xl shadow-sm p-5 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-black text-slate-800 text-sm uppercase tracking-widest"><i class="fa-solid fa-headset text-amber-500 mr-1"></i> Live IT Helpdesk</h3>
                </div>
                <a href="{{ route('tickets.index') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">View Tickets</a>
            </div>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar pr-2 max-h-[300px]">
                @forelse($activeTickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="block p-3 border border-slate-200 rounded-lg hover:border-blue-300 transition-all bg-slate-50">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-[9px] font-black uppercase text-blue-600">{{ $ticket->ticket_number }}</span>
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded {{ $ticket->status === 'Processing' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $ticket->status }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs mb-2">{{ $ticket->title }}</h4>
                        <div class="flex items-center justify-between text-[9px] text-slate-500 font-bold">
                            <span><i class="fa-solid fa-ship mr-1"></i> {{ $ticket->asset->vessel->vessel_name ?? 'Head Office' }}</span>
                            <span><i class="fa-regular fa-clock mr-1"></i> {{ $ticket->created_at->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        <i class="fa-solid fa-check-circle text-4xl mb-2 text-emerald-400"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest">Tidak ada tiket insiden aktif.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="xl:col-span-2 bg-white border-2 border-slate-200 rounded-xl shadow-sm p-5">
            <h3 class="font-black text-slate-800 text-sm uppercase tracking-widest mb-4"><i class="fa-solid fa-ship text-blue-500 mr-1"></i> Real-time Fleet Health</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                @foreach($vessels as $vessel)
                    @php
                        $isDown = $activeTickets->contains('asset.vessel_id', $vessel->id);
                    @endphp
                    <div class="flex items-center justify-between p-3 border rounded-lg {{ $isDown ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid {{ $isDown ? 'fa-triangle-exclamation text-red-500' : 'fa-check text-emerald-500' }}"></i>
                            <div>
                                <h4 class="font-black text-slate-800 text-xs">{{ $vessel->vessel_name }}</h4>
                                <span class="text-[8px] font-black uppercase tracking-widest {{ $isDown ? 'text-red-500' : 'text-emerald-600' }}">{{ $isDown ? 'Downtime Detected' : 'Optimal' }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="mb-4 border-t-2 border-slate-200 pt-8 flex items-center gap-3 border-b-2 pb-2">
        <i class="fa-solid fa-file-signature text-orange-500 text-xl"></i>
        <h2 class="text-lg font-black text-slate-800 tracking-widest uppercase">Weekly Compliance & Reports</h2>
        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[9px] font-black rounded uppercase ml-2">Historical Data</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6 animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="bg-white border-2 border-slate-200 border-l-4 border-l-blue-600 shadow-sm rounded-xl p-4 flex flex-col justify-between">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Monitored</p>
            <h3 class="text-3xl font-black text-slate-900">{{ $totalVessels }} <i class="fa-solid fa-ship text-blue-100 float-right mt-1"></i></h3>
        </div>
        <div class="bg-white border-2 border-slate-200 border-l-4 border-l-orange-500 shadow-sm rounded-xl p-4 flex flex-col justify-between">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Draft Reports</p>
            <h3 class="text-3xl font-black text-slate-900">{{ $draftCount }} <i class="fa-solid fa-file-pen text-orange-100 float-right mt-1"></i></h3>
        </div>
        <div class="bg-white border-2 border-slate-200 border-l-4 border-l-red-600 shadow-sm rounded-xl p-4 flex flex-col justify-between">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Reported Incidents</p>
            <h3 class="text-3xl font-black text-red-600">{{ $incidentCount }} <i class="fa-solid fa-fire text-red-100 float-right mt-1"></i></h3>
        </div>
        <div class="bg-white border-2 border-slate-200 border-l-4 border-l-emerald-500 shadow-sm rounded-xl p-4 flex flex-col justify-between">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Avg Reported Uptime</p>
            <h3 class="text-3xl font-black text-slate-900">{{ $avgUptime }}<span class="text-base text-slate-400">%</span> <i class="fa-solid fa-chart-line text-emerald-100 float-right mt-1"></i></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="lg:col-span-2 bg-white border-2 border-slate-200 p-5 rounded-xl shadow-sm">
            <div class="mb-2">
                <h3 class="font-black text-sm text-slate-800 uppercase tracking-widest">Reported Uptime Trend (4 Weeks)</h3>
            </div>
            <div id="chart-uptime" class="w-full h-56"></div>
        </div>

        <div class="bg-white border-2 border-slate-200 p-5 rounded-xl shadow-sm flex flex-col">
            <h3 class="font-black text-sm text-slate-800 uppercase tracking-widest mb-4">Report Audit Trail</h3>
            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar pr-2 max-h-[220px]">
                @forelse($recentActivities as $activity)
                    @php
                        $colorClass = $activity->status == 1 ? 'border-orange-500 text-orange-600' : 'border-emerald-500 text-emerald-600';
                        $actionText = $activity->status == 1 ? 'Draft Auto-Saved' : 'Report Submitted';
                    @endphp
                    <div class="border-l-4 {{ $colorClass }} pl-3 bg-slate-50 p-2 rounded-r-lg">
                        <p class="text-xs font-black text-slate-800">{{ $actionText }}: {{ $activity->vessel->vessel_name }}</p>
                        <p class="text-[9px] text-slate-500 font-bold mt-0.5"><i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="text-center p-4 text-xs font-black text-slate-400 uppercase">Belum ada aktivitas</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="p-4 border-b-2 border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="font-black text-sm text-slate-800 uppercase tracking-widest">Fleet Report Status (This Week)</h3>
            <a href="{{ route('reports.index') }}" class="px-4 py-1.5 bg-white border border-slate-300 rounded text-[10px] font-black text-slate-600 hover:bg-slate-100 uppercase transition-colors shadow-sm">Manage Reports</a>
        </div>
        <div class="overflow-x-auto min-h-[200px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-500 uppercase bg-white border-b-2 border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-5 py-3 font-black w-10">No</th>
                        <th class="px-5 py-3 font-black">Vessel / Company</th>
                        <th class="px-5 py-3 font-black text-center">Submission Progress</th>
                        <th class="px-5 py-3 font-black text-center">Assigned PIC</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($vesselReports as $index => $item)
                    @php
                        $vessel = $item->vessel;
                        $step = $item->status;
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-3 font-black text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-5 py-3">
                            <div class="font-black text-blue-800 text-sm">{{ $vessel->vessel_name }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase">{{ $vessel->company_name }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-center w-full max-w-[200px] mx-auto opacity-80">
                                <div class="flex items-center"><div class="flex items-center justify-center w-5 h-5 rounded-full border-2 {{ $step >= 1 ? 'bg-orange-500 border-orange-600 text-white' : 'bg-slate-200 border-slate-300 text-slate-400' }} font-black text-[9px]">1</div></div>
                                <div class="w-10 h-1 {{ $step >= 3 ? 'bg-orange-500' : 'bg-slate-200' }}"></div>
                                <div class="flex items-center"><div class="flex items-center justify-center w-5 h-5 rounded-full border-2 {{ $step >= 3 ? 'bg-emerald-500 border-emerald-600 text-white' : 'bg-slate-200 border-slate-300 text-slate-400' }} font-black text-[9px]">
                                    <i class="fa-solid fa-check text-[8px]"></i>
                                </div></div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-center">
                            @php
                                $isLevi = str_contains(strtolower($vessel->pic_name), 'levi');
                                $badgeClass = $isLevi ? 'bg-indigo-100 text-indigo-800 border-indigo-300' : 'bg-slate-100 text-slate-600 border-slate-300';
                            @endphp
                            <span class="px-2 py-1 rounded text-[9px] font-black border uppercase {{ $badgeClass }}">
                                {{ $vessel->pic_name }}
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
        var chartLabels = {!! json_encode($chartLabels) !!};
        var chartData = {!! json_encode($chartData) !!};

        var optionsUptime = {
            series: [{ name: 'Avg Uptime (%)', data: chartData }],
            chart: { type: 'area', height: 220, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
            colors: ['#f97316'], // Ubah warna jadi orange agar beda dengan ITSM
            fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
            dataLabels: { enabled: false }, stroke: { curve: 'smooth', width: 3 },
            xaxis: {
                categories: chartLabels,
                labels: { style: { fontSize: '10px', fontWeight: 800, colors: '#64748b' } },
                axisBorder: { show: false }, axisTicks: { show: false }
            },
            yaxis: { show: true, min: 90, max: 100, labels: { style: { fontSize: '10px', fontWeight: 800, colors: '#64748b' } } },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: true } } },
            tooltip: { theme: 'light', y: { formatter: function (val) { return val + "%" } } }
        };
        new ApexCharts(document.querySelector("#chart-uptime"), optionsUptime).render();
    });
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
