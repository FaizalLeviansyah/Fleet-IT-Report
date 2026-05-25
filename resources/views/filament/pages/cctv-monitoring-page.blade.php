<x-filament-panels::page>
    <style>
        /* Desain UI Adaptasi dari Kodingan Teman Anda */
        .monitor-wrapper { font-family: 'Inter', sans-serif; }

        .glass-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #e1e4e8; }
        .dark .glass-card { background: #1E293B; border-color: #334155; }

        .monitor-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 768px) { .monitor-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1280px) { .monitor-grid { grid-template-columns: repeat(3, 1fr); } }

        .cam-card { background: white; border-radius: 12px; overflow: hidden; border: 1px solid #dee2e6; box-shadow: 0 8px 15px rgba(0,0,0,0.05); }
        .dark .cam-card { background: #0F172A; border-color: #1E293B; }

        .cam-header { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .dark .cam-header { border-color: #1E293B; }
        .status-live { font-size: 10px; font-weight: 800; color: #10b981; text-transform: uppercase; }

        .img-box { width: 100%; aspect-ratio: 16 / 9; background: #000; position: relative; overflow: hidden; }

        /* Animasi Transisi Alpine.js */
        .snap-img { width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.5s ease-in-out; }
        .snap-img.active { opacity: 1; z-index: 5; }

        .cam-footer { padding: 10px 15px; background: #fafafa; border-top: 1px solid #eee; display: flex; align-items: center; gap: 8px; font-size: 12px; }
        .dark .cam-footer { background: #1E293B; border-color: #334155; }
        .time-val { font-weight: 700; color: #38BDF8; }

        /* Filter Form - LIVEWIRE MODE */
        .f-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 120px; }
        .f-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; }
        .f-input { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background: transparent; }
        .dark .f-input { border-color: #475569; color: white; }
    </style>

    <div class="monitor-wrapper">
        <div class="glass-card">
            <h5 class="mb-4 font-bold text-slate-800 dark:text-slate-200">
                <svg class="w-5 h-5 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Monitoring Filter
            </h5>
            <div class="flex flex-wrap gap-4">
                <div class="f-group">
                    <label class="f-label">Vessel</label>
                    <select wire:model.live="selected_vessel" class="f-input">
                        <option value="">Pilih Kapal...</option>
                        @foreach($daftar_kapal as $k)
                            <option value="{{ $k->vessel_name }}">{{ $k->vessel_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="f-group">
                    <label class="f-label">Start Date</label>
                    <input type="date" wire:model.live="start_date" class="f-input">
                </div>
                <div class="f-group">
                    <label class="f-label">End Date</label>
                    <input type="date" wire:model.live="end_date" class="f-input">
                </div>
                <div class="f-group">
                    <label class="f-label">Start Time</label>
                    <input type="time" wire:model.live="start_time" class="f-input">
                </div>
                <div class="f-group">
                    <label class="f-label">End Time</label>
                    <input type="time" wire:model.live="end_time" class="f-input">
                </div>
                </div>
        </div>

        <div class="monitor-grid">
            @forelse($channels as $ch)
                @php
                    $images = $data_per_channel[$ch];
                    $totalImages = count($images);
                @endphp

                <div class="cam-card" x-data="{ currentSlide: 0, total: {{ $totalImages }} }" x-init="if(total > 1) { setInterval(() => { currentSlide = (currentSlide + 1) % total }, 4000) }">

                    <div class="cam-header">
                        <span class="font-black text-slate-700 dark:text-slate-200 uppercase">
                            <svg class="w-4 h-4 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            {{ $ch }}
                        </span>
                        <span class="status-live"><span class="inline-block w-2 h-2 bg-green-500 rounded-full animate-pulse mr-1"></span> Live</span>
                    </div>

                    <div class="img-box">
                        @if($totalImages > 0)
                            @foreach($images as $index => $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     class="snap-img"
                                     :class="{ 'active': currentSlide === {{ $index }} }"
                                     alt="CCTV Snapshot">
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-500">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                <span class="text-[10px] font-bold uppercase tracking-wider">No Connection / Data</span>
                            </div>
                        @endif
                    </div>

                    <div class="cam-footer">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-slate-500 dark:text-slate-400">Timestamp: </span>

                        @foreach($images as $index => $img)
                            <span class="time-val" x-show="currentSlide === {{ $index }}" x-cloak>
                                {{ \Carbon\Carbon::parse($img->captured_at)->format('d M Y, H:i:s') }}
                            </span>
                        @endforeach
                        @if($totalImages == 0)
                            <span class="time-val">-</span>
                        @endif
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center p-8 text-gray-500 bg-white dark:bg-slate-800 rounded-xl border border-dashed border-gray-300 dark:border-slate-600">
                    <p class="font-bold">Belum ada data snapshot CCTV untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
