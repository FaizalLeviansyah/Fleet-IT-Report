@extends('portal.layouts.app')
@section('page_title', 'Aset IT Saya')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">My IT Assets</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Daftar perangkat keras dan perangkat lunak yang diamanahkan kepada Anda.</p>
        </div>
        <div class="bg-slate-50 px-5 py-2.5 rounded-xl border border-slate-200 text-center">
            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Total Perangkat</p>
            <p class="text-xl font-black text-blue-600">{{ $assets->count() }} <span class="text-sm font-bold text-slate-500">Unit</span></p>
        </div>
    </div>

    <!-- GRID ASSETS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assets as $asset)
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all overflow-hidden flex flex-col">
            
            <!-- Card Header (Visual Warna & Icon) -->
            <div class="h-24 bg-gradient-to-br from-slate-800 to-slate-900 p-5 relative overflow-hidden flex justify-between items-start">
                <div class="absolute -right-4 -bottom-4 opacity-10 text-7xl text-white">
                    <i class="fas {{ str_contains(strtolower($asset->asset_type), 'laptop') ? 'fa-laptop' : (str_contains(strtolower($asset->asset_type), 'printer') ? 'fa-print' : 'fa-server') }}"></i>
                </div>
                <div class="relative z-10">
                    <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm text-white text-[10px] font-black uppercase rounded-lg border border-white/10">{{ $asset->asset_type }}</span>
                </div>
                <div class="relative z-10">
                    @if($asset->status === 'Active')
                        <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Online</span>
                    @else
                        <span class="flex items-center gap-1.5 text-xs font-bold text-red-400"><span class="w-2 h-2 rounded-full bg-red-400"></span> Offline</span>
                    @endif
                </div>
            </div>

            <!-- Identitas Perangkat -->
            <div class="px-6 pt-4 pb-2 border-b border-slate-50">
                <h3 class="text-lg font-black text-slate-800 leading-tight">{{ $asset->asset_name }}</h3>
                <p class="text-xs font-bold text-blue-600 mt-1">{{ $asset->manufacturer }} {{ $asset->model }}</p>
            </div>

            <!-- Spesifikasi Teknis -->
            <div class="p-6 space-y-4 flex-1">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 border border-slate-100"><i class="fas fa-microchip"></i></div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Processor (CPU)</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5 line-clamp-1" title="{{ $asset->cpu_model }}">{{ $asset->cpu_model ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 border border-slate-100"><i class="fas fa-memory"></i></div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Installed RAM</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $asset->total_ram ?? '-' }} GB</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 border border-slate-100"><i class="fas fa-network-wired"></i></div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">IP Address</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $asset->ip_address ?? 'DHCP / No Net' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 flex-shrink-0 border border-slate-100"><i class="fab fa-windows"></i></div>
                    <div>
                        <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">OS Version</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $asset->os_version ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 mt-auto flex justify-between items-center">
                <span class="text-[10px] font-bold text-slate-400"><i class="fas fa-sync-alt mr-1"></i> Sync: {{ $asset->last_seen ? \Carbon\Carbon::parse($asset->last_seen)->diffForHumans() : '-' }}</span>
                <a href="{{ route('portal.create-ticket', ['asset' => $asset->id]) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Lapor Kendala &rarr;</a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-300 mb-4 border border-slate-100">
                <i class="fas fa-box-open text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Tidak Ada Aset</h3>
            <p class="text-slate-500 font-medium text-sm">Belum ada perangkat IT yang ditugaskan kepada Anda atau Agent belum mendeteksi laptop Anda.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection