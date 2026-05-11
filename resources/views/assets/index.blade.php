@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                @php
                    $currentIcon = $categories->where('name', $selectedCategory)->first()->icon ?? 'fa-box';
                @endphp
                <i class="fa-solid {{ $currentIcon }} text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ strtoupper($selectedCategory) }}</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1">Management & Configuration Database</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <div class="text-xs font-black text-slate-700 uppercase">Online: <span class="text-emerald-600">{{ $assets->filter(fn($a) => $a->last_seen && \Carbon\Carbon::parse($a->last_seen)->diffInHours(now()) <= 2)->count() }}</span></div>
            </div>
            <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="text-xs font-black text-slate-700 uppercase">Offline: <span class="text-red-600">{{ $assets->filter(fn($a) => !$a->last_seen || \Carbon\Carbon::parse($a->last_seen)->diffInHours(now()) > 2)->count() }}</span></div>
            </div>
            <button class="px-5 py-2 bg-blue-600 text-white border-2 border-blue-800 rounded-xl text-[11px] font-black uppercase hover:bg-blue-700 hover:scale-105 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add {{ $selectedCategory }}
            </button>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-2xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto custom-scrollbar min-h-[400px]">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400">
                    <tr>
                        <th class="px-6 py-4 font-black">Name / Status</th>
                        <th class="px-6 py-4 font-black">Network / IP</th>
                        <th class="px-6 py-4 font-black">Manufacturer & Model</th>
                        <th class="px-6 py-4 font-black">Serial Number</th>
                        <th class="px-6 py-4 font-black">Location</th>
                        <th class="px-6 py-4 font-black">Spesifikasi CPU & RAM</th>
                        <th class="px-6 py-4 font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($assets as $asset)
                        @php
                            $isOnline = $asset->last_seen && \Carbon\Carbon::parse($asset->last_seen)->diffInHours(now()) <= 2;
                        @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-black text-slate-900 text-sm">{{ $asset->asset_name }}</div>
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                                <span class="text-[9px] font-bold {{ $isOnline ? 'text-emerald-600' : 'text-red-500' }} uppercase tracking-widest">{{ $isOnline ? 'ONLINE' : 'OFFLINE' }}</span>
                                <span class="text-[9px] font-bold text-slate-400">| User: {{ $asset->current_user ?: 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $asset->ip_address ?: '-' }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase mt-1">MAC: {{ $asset->mac_address ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">{{ $asset->manufacturer ?: '-' }}</div>
                            <div class="text-[10px] text-slate-500">{{ $asset->model ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600 font-bold">
                            {{ $asset->serial_number ?: '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded text-[10px] font-black text-slate-600 uppercase italic">
                                <i class="fa-solid fa-location-dot mr-1"></i> {{ $asset->location->name ?? 'Unassigned' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-[11px] truncate w-48" title="{{ $asset->cpu_model }}"><i class="fa-solid fa-microchip w-4 text-slate-400"></i> {{ $asset->cpu_model ?: '-' }}</div>
                            <div class="text-[10px] text-blue-600 font-black bg-blue-50 inline-block px-1.5 py-0.5 rounded border border-blue-200 mt-1"><i class="fa-solid fa-memory w-3"></i> RAM: {{ $asset->total_ram ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 shadow-sm transition-all" title="Edit Asset"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button onclick="openAssetModal({{ $asset->toJson() }})" class="px-3 py-2 bg-slate-800 text-white border border-slate-700 hover:bg-slate-700 rounded-lg font-bold transition-all shadow-sm text-[10px] uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-eye"></i> X-Ray
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-500 font-bold">Belum ada perangkat dalam kategori ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
