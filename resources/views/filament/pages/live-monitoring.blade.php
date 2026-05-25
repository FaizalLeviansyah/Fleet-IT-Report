<x-filament-panels::page>
    <style>
        .monitor-wrapper { font-family: 'Inter', sans-serif; }

        /* Widget Stats Realtime */
        .stats-row { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-card { background: white; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px 20px; flex: 1; min-width: 250px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .dark .stat-card { background: #1E293B; border-color: #334155; }
        .stat-value { font-size: 18px; font-weight: 900; color: #1e293b; line-height: 1.2; margin-top: 4px; }
        .dark .stat-value { color: white; }
        .stat-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Panel Filter */
        .glass-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #e1e4e8; }
        .dark .glass-card { background: #1E293B; border-color: #334155; }
        .filter-form { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .f-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 140px; }
        .f-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; }
        .f-input { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background: transparent; width: 100%; transition: border-color 0.2s;}
        .dark .f-input { border-color: #475569; color: white; }
        .f-input:focus { border-color: #2563EB; outline: none; }

        .btn-apply { background: #2563EB; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: 700; cursor: pointer; transition: 0.2s; height: 39px; display: flex; align-items: center; gap: 8px;}
        .btn-apply:hover { background: #1D4ED8; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }

        /* Grid CCTV */
        .monitor-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 768px) { .monitor-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1280px) { .monitor-grid { grid-template-columns: repeat(3, 1fr); } }

        .cam-card { background: white; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.03); display: flex; flex-direction: column; }
        .dark .cam-card { background: #0F172A; border-color: #1E293B; }

        /* Header Card CCTV */
        .cam-header { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;}
        .dark .cam-header { border-color: #1E293B; background: #0F172A;}
        .cam-title { font-size: 13px; font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .dark .cam-title { color: #f8fafc; }
        .status-live { font-size: 10px; font-weight: 800; color: #10b981; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }

        /* Container Gambar CCTV */
        .img-box { width: 100%; aspect-ratio: 16 / 9; background: #050505; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .snap-img { width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.4s ease; }
        .snap-img.active { opacity: 1; z-index: 5; }

        .no-connection { text-align: center; color: #475569; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 10;}
        .no-connection svg { width: 40px; height: 40px; margin-bottom: 8px; opacity: 0.5; }
        .no-connection span { font-size: 11px; font-weight: 700; letter-spacing: 1px; }

        /* Footer Keterangan Ekstra */
        .cam-footer { padding: 12px 15px; background: #fafafa; border-top: 1px solid #f1f5f9; flex-grow: 1; display: flex; flex-direction: column; justify-content: center; gap: 8px; }
        .dark .cam-footer { background: #1E293B; border-color: #334155; }

        .footer-row { display: flex; justify-content: space-between; align-items: center; font-size: 11px; }
        .info-label { color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 4px;}
        .info-value { font-weight: 800; color: #0f172a; }
        .dark .info-value { color: #e2e8f0; }

        /* Indikator Sinyal / Network Health */
        .signal-good { color: #10b981; background: #d1fae5; padding: 2px 6px; border-radius: 4px; }
        .dark .signal-good { background: #064e3b; color: #34d399; }
        .signal-delay { color: #f59e0b; background: #fef3c7; padding: 2px 6px; border-radius: 4px; }
        .dark .signal-delay { background: #78350f; color: #fbbf24; }
    </style>

    <div class="monitor-wrapper">

        <div class="stats-row">
            <div class="stat-card">
                <div>
                    <div class="stat-label">Target Monitoring</div>
                    <div class="stat-value">{{ $selected_vessel ?: 'Belum Dipilih' }}</div>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full text-blue-600 dark:text-blue-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">Last Synchronization</div>
                    <div class="stat-value text-green-600 dark:text-green-400">{{ $last_sync }}</div>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full text-green-600 dark:text-green-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div>
                    <div class="stat-label">CCTV Online Status</div>
                    <div class="stat-value">{{ $total_active_cams }} / {{ count($channels) }} Kamera Aktif</div>
                </div>
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-full text-orange-600 dark:text-orange-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <h5 class="mb-4 font-bold text-slate-800 dark:text-slate-200">
                <svg class="w-5 h-5 inline mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Monitoring Filter
            </h5>

            <form wire:submit="applyFilter" class="filter-form">
                <div class="f-group">
                    <label class="f-label">Pilih Vessel</label>
                    <select wire:model.live="selected_vessel" class="f-input" required>
                        <option value="">Pilih Kapal...</option>
                        @foreach($daftar_kapal as $k)
                            <option value="{{ $k->vessel_name }}">{{ $k->vessel_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="f-group">
                    <label class="f-label">Start Date</label>
                    <input type="date" wire:model="start_date" class="f-input" required>
                </div>
                <div class="f-group">
                    <label class="f-label">End Date</label>
                    <input type="date" wire:model="end_date" class="f-input" required>
                </div>
                <div class="f-group">
                    <label class="f-label">Start Time</label>
                    <input type="time" wire:model="start_time" class="f-input">
                </div>
                <div class="f-group">
                    <label class="f-label">End Time</label>
                    <input type="time" wire:model="end_time" class="f-input">
                </div>
                <button type="submit" class="btn-apply">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    TAMPILKAN
                </button>
            </form>
        </div>

        <div class="monitor-grid">
            @foreach($channels as $ch)
                @php
                    $images = $data_per_channel[$ch] ?? collect();
                    $totalImages = count($images);
                    // Ambil nama lengkap dari array controller, jika tidak ada pakai kode aslinya
                    $fullName = $channel_labels[$ch] ?? $ch;
                @endphp

                <div class="cam-card"
                     x-data="{ currentSlide: 0, total: {{ $totalImages }} }"
                     x-init="if(total > 1) { setInterval(() => { currentSlide = (currentSlide + 1) % total }, 4000) }">

                    <div class="cam-header">
                        <div class="cam-title">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            {{ $fullName }}
                        </div>
                        <div class="status-live">
                            <span class="inline-block w-2 h-2 rounded-full {{ $totalImages > 0 ? 'bg-green-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                            {{ $totalImages > 0 ? 'LIVE' : 'OFFLINE' }}
                        </div>
                    </div>

                    <div class="img-box">
                        @if($totalImages > 0)
                            @foreach($images as $index => $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     class="snap-img"
                                     :class="{ 'active': currentSlide === {{ $index }} }"
                                     alt="{{ $fullName }} Snapshot">
                            @endforeach
                        @else
                            <div class="no-connection">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                <span>NO CONNECTION</span>
                            </div>
                        @endif
                    </div>

                    <div class="cam-footer">
                        <div class="footer-row border-b border-gray-100 dark:border-slate-700 pb-1.5">
                            <div class="info-label">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Updated At:
                            </div>
                            <div class="info-value">
                                @if($totalImages > 0)
                                    @foreach($images as $index => $img)
                                        <span x-show="currentSlide === {{ $index }}" x-cloak class="text-blue-600 dark:text-blue-400">
                                            {{ \Carbon\Carbon::parse($img->captured_at)->format('d M Y - H:i:s') }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="footer-row">
                            <div class="info-label">Network Status:</div>
                            <div class="info-value">
                                @if($totalImages > 0)
                                    @foreach($images as $index => $img)
                                        @php
                                            // Menghitung Jeda Internet (Delay)
                                            $delay = \Carbon\Carbon::parse($img->created_at)->diffInMinutes(\Carbon\Carbon::parse($img->captured_at));
                                            $isGood = $delay < 60; // Jika jeda kirim ke server kurang dari 1 jam = Bagus
                                        @endphp
                                        <span x-show="currentSlide === {{ $index }}" x-cloak class="{{ $isGood ? 'signal-good' : 'signal-delay' }}">
                                            {{ $isGood ? '🟢 Stabil' : '🟠 Delayed ('.$delay.'m)' }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400">Terputus</span>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </div>
</x-filament-panels::page>
