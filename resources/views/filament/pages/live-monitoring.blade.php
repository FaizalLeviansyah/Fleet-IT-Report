<x-filament-panels::page>
    <style>
        .monitor-wrapper { font-family: 'Inter', sans-serif; }
        .stats-row { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        .stat-card { background: white; border: 1px solid #e1e4e8; border-radius: 10px; padding: 15px 20px; flex: 1; min-width: 250px; display: flex; align-items: center; justify-content: space-between; }
        .dark .stat-card { background: #1E293B; border-color: #334155; }
        .stat-value { font-size: 18px; font-weight: 900; color: #1e293b; margin-top: 4px; }
        .dark .stat-value { color: white; }
        .stat-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }

        .glass-card { background: white; border-radius: 12px; padding: 20px; margin-bottom: 25px; border: 1px solid #e1e4e8; }
        .dark .glass-card { background: #1E293B; border-color: #334155; }
        .filter-form { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .f-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 140px; }
        .f-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; }

        .f-input { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background: transparent; width: 100%; transition: 0.2s; }
        select.f-input {
            appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 1rem center; background-size: 1em; padding-right: 2.5rem;
        }
        .dark .f-input { border-color: #475569; color: white; }
        .dark select.f-input { background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23cbd5e1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); }
        .f-input:focus { border-color: #2563EB; outline: none; }

        .btn-apply { background: #2563EB; color: white; padding: 10px 25px; border-radius: 6px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;}
        .btn-apply:hover { background: #1D4ED8; }

        .monitor-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 768px) { .monitor-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1280px) { .monitor-grid { grid-template-columns: repeat(3, 1fr); } }

        .cam-card { background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column; cursor: pointer; transition: all 0.2s ease-in-out; }
        .cam-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(37, 99, 235, 0.15); border-color: #3b82f6; }
        .dark .cam-card { background: #0F172A; border-color: #1E293B; }
        .dark .cam-card:hover { border-color: #3b82f6; }

        .cam-header { padding: 12px 15px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;}
        .dark .cam-header { border-color: #1E293B; background: #0F172A;}
        .cam-title { font-size: 13px; font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .dark .cam-title { color: #f8fafc; }
        .status-live { font-size: 10px; font-weight: 800; color: #10b981; text-transform: uppercase; display: flex; align-items: center; gap: 5px; }

        .img-box { width: 100%; aspect-ratio: 16 / 9; background: #050505; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .snap-img { width: 100%; height: 100%; object-fit: contain; position: absolute; top: 0; left: 0; opacity: 0; transition: opacity 0.5s ease-in-out; }
        .snap-img.active { opacity: 1; z-index: 5; }
        .play-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; z-index: 10;}
        .img-box:hover .play-overlay { opacity: 1; }
        .play-overlay svg { width: 48px; height: 48px; color: white; drop-shadow: 0 4px 6px rgba(0,0,0,0.5); }

        .cam-footer { padding: 12px 15px; background: #fafafa; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 8px; }
        .dark .cam-footer { background: #1E293B; border-color: #334155; }
        .footer-row { display: flex; justify-content: space-between; align-items: center; font-size: 11px; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-value { font-weight: 800; color: #2563EB; }
        .dark .info-value { color: #38BDF8; }

        /* THEATER MODE FULLSCREEN ABSOLUTE BLACK */
        .theater-overlay { position: fixed; inset: 0; z-index: 99999; background-color: #000; display: flex; flex-col; width: 100vw; height: 100vh; }
        .theater-header { position: absolute; top: 0; left: 0; right: 0; padding: 20px 30px; background: linear-gradient(to bottom, rgba(0,0,0,0.9), transparent); display: flex; justify-content: space-between; align-items: center; z-index: 10; }
        .theater-footer { position: absolute; bottom: 0; left: 0; right: 0; padding: 20px 30px; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); display: flex; justify-content: space-between; align-items: center; z-index: 10; }
    </style>

    <div x-data="{
        isModalOpen: false,
        activeChannel: '',
        images: [],
        currentIndex: 0,
        interval: null,
        isPlaying: true,

        globalSync: false,
        globalIndex: 0,
        globalTimer: null,

        toggleGlobalSync() {
            this.globalSync = !this.globalSync;
            if(this.globalSync) {
                this.globalIndex = 0;
                this.globalTimer = setInterval(() => { this.globalIndex++; }, 7500);
                new FilamentNotification().title('Auto-Sync Menyala 🟢').body('Timeline CCTV berjalan bersamaan.').success().send();
            } else {
                clearInterval(this.globalTimer);
                new FilamentNotification().title('Auto-Sync Mati 🔴').body('Kembali ke mode Thumbnail Kamera Terbaru.').warning().send();
            }
        },

        openTheater(channelName, dataImages) {
            if(dataImages.length === 0) {
                new FilamentNotification().title('Kamera Offline').body('Tidak ada foto untuk rentang waktu ini.').danger().send();
                return;
            }
            this.activeChannel = channelName;
            this.images = dataImages;
            this.currentIndex = dataImages.length - 1; // Mulai dari frame terupdate
            this.isModalOpen = true;
            this.startSlideshow();
        },
        closeTheater() {
            this.isModalOpen = false;
            clearInterval(this.interval);
        },
        startSlideshow() {
            this.isPlaying = true;
            clearInterval(this.interval);
            if(this.images.length > 1) {
                this.interval = setInterval(() => { this.nextSlide(); }, 5000);
            }
        },
        pauseSlideshow() {
            this.isPlaying = false;
            clearInterval(this.interval);
        },
        togglePlay() {
            if(this.isPlaying) {
                this.pauseSlideshow();
            } else {
                this.startSlideshow();
            }
        },
        nextSlide() { this.currentIndex = (this.currentIndex + 1) % this.images.length; },
        prevSlide() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; }
    }">

        <div class="stats-row">
            <div class="stat-card">
                <div><div class="stat-label">Target Monitoring</div><div class="stat-value">{{ $selected_vessel ?: 'Belum Dipilih' }}</div></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">Last Synchronization</div><div class="stat-value text-green-600 dark:text-green-400">{{ $last_sync }}</div></div>
            </div>
            <div class="stat-card">
                <div><div class="stat-label">CCTV Snapshoot Status</div><div class="stat-value">{{ $total_active_cams }} / {{ count($channels) }} Kamera Aktif</div></div>
            </div>
        </div>

        <div class="glass-card">
            <h5 class="mb-4 font-bold text-slate-800 dark:text-slate-200">Monitoring Filter</h5>
            <form wire:submit="applyFilter" class="filter-form">
                <div class="f-group">
                    <label class="f-label">Choose Vessels</label>
                    <select wire:model.live="selected_vessel" class="f-input" required>
                        <option value="">Choose Vessels..</option>
                        @foreach($daftar_kapal as $k)
                            <option value="{{ $k->vessel_name }}">{{ $k->vessel_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="f-group"><label class="f-label">Start Date</label><input type="date" wire:model="start_date" class="f-input" required></div>
                <div class="f-group"><label class="f-label">End Date</label><input type="date" wire:model="end_date" class="f-input" required></div>
                <div class="f-group"><label class="f-label">Start Time</label><input type="time" wire:model="start_time" class="f-input"></div>
                <div class="f-group"><label class="f-label">End Time</label><input type="time" wire:model="end_time" class="f-input"></div>
                <button type="submit" class="btn-apply">Apply</button>
            </form>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-slate-800 p-5 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm mb-6 mt-4">

            <div class="flex items-center gap-4">
                <button @click="toggleGlobalSync()" type="button" class="relative inline-flex h-8 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none" :class="globalSync ? 'bg-blue-600' : 'bg-slate-300 dark:bg-slate-600'">
                    <span class="pointer-events-none inline-block h-7 w-7 transform rounded-full bg-white shadow ring-0 transition duration-300 ease-in-out" :class="globalSync ? 'translate-x-6' : 'translate-x-0'"></span>
                </button>
                <div>
                    <h6 class="font-bold text-slate-800 dark:text-slate-200 text-sm flex items-center gap-2">
                        GLOBAL AUTO-SYNC
                        <span x-show="globalSync" class="flex h-2.5 w-2.5 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span></span>
                    </h6>
                    <p class="text-[11px] text-slate-500 font-medium tracking-wide">Play All Cameras Together (7.5 Second)</p>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-4 md:mt-0 bg-slate-50 dark:bg-slate-900 p-2 px-4 rounded-lg border border-slate-100 dark:border-slate-800">
                <label class="text-xs font-bold text-slate-500 uppercase flex items-center gap-1">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Frame Distance
                </label>
                <select wire:model.live="frame_interval" class="f-input !py-1.5 !px-2 !w-auto !border-none !shadow-none !bg-transparent text-sm font-bold text-slate-700 dark:text-slate-200 cursor-pointer">
                    <option value="all">Realtime (All Frame)</option>
                    <option value="hourly">Per 1 Hour</option>
                    <option value="half_day">Per 12 Hour</option>
                    <option value="daily">Per 1 Day</option>
                </select>
            </div>

        </div>

        <div class="monitor-grid">
            @foreach($channels as $ch)
                @php
                    $images = $data_per_channel[$ch] ?? collect();
                    $totalImages = count($images);
                    $fullName = $channel_labels[$ch] ?? $ch;

                    $alpineImages = $images->map(function($img) {
                        return [
                            'url' => asset('storage/' . $img->image_path),
                            'time' => \Carbon\Carbon::parse($img->captured_at)->format('d M Y - h:i:s A'),
                            'status' => 'REC 🔴'
                        ];
                    })->values()->toJson();
                @endphp

                <div class="cam-card !cursor-default">
                    <div class="cam-header">
                        <div class="cam-title w-full mr-4">
                            <input type="text"
                                   wire:model.blur="channel_labels.{{ $ch }}"
                                   class="bg-transparent border-none focus:ring-0 p-0 m-0 w-full text-[13px] font-black text-slate-800 dark:text-slate-200 uppercase tracking-wide hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-text rounded px-1"
                                   placeholder="NAMA KAMERA" />
                        </div>
                        <div class="status-live shrink-0">
                            <span class="inline-block w-2 h-2 rounded-full {{ $totalImages > 0 ? 'bg-green-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                            {{ $totalImages > 0 ? 'LIVE' : 'OFFLINE' }}
                        </div>
                    </div>

                    <div class="img-box cursor-pointer" data-images="{{ $alpineImages }}" @click="openTheater('{{ $fullName }}', JSON.parse($el.dataset.images))">
                        @if($totalImages > 0)
                            @foreach($images as $index => $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     class="snap-img"
                                     :class="{ 'active': globalSync ? ({{ $index }} === (globalIndex % {{ $totalImages }})) : ({{ $index }} === {{ $totalImages - 1 }}) }"
                                     alt="{{ $fullName }}">
                            @endforeach
                            <div class="play-overlay">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        @else
                            <div class="text-slate-500 text-[11px] font-bold tracking-widest text-center">NO CONNECTION</div>
                        @endif
                    </div>

                    <div class="cam-footer">
                        <div class="footer-row border-b border-gray-100 dark:border-slate-700 pb-1.5">
                            <div class="info-label">Updated Frame At = </div>
                            <div class="info-value text-[10px]">
                                @if($totalImages > 0)
                                    @foreach($images as $index => $img)
                                        <span x-show="globalSync ? ({{ $index }} === (globalIndex % {{ $totalImages }})) : ({{ $index }} === {{ $totalImages - 1 }})" x-cloak>
                                            {{ \Carbon\Carbon::parse($img->captured_at)->format('d M Y - h:i A') }}
                                        </span>
                                    @endforeach
                                @else
                                    <span>-</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div x-show="isModalOpen" x-cloak class="theater-overlay" x-transition.opacity.duration.300ms>

            <div class="theater-header">
                <div class="flex items-center gap-4">
                    <span class="w-4 h-4 bg-red-600 rounded-full animate-pulse shadow-[0_0_10px_rgba(220,38,38,0.8)]"></span>
                    <h2 class="text-white font-black text-2xl tracking-wider" x-text="activeChannel"></h2>
                    <span class="text-slate-300 font-mono text-sm ml-2 bg-slate-800 px-3 py-1 rounded" x-text="'Frame ' + (currentIndex + 1) + ' / ' + images.length"></span>
                </div>
                <button @click="closeTheater()" class="text-white hover:text-red-500 bg-white/10 hover:bg-white/20 p-2 rounded-full transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="relative w-full h-full flex items-center justify-center bg-black">
                <template x-for="(img, index) in images" :key="index">
                    <img :src="img.url" x-show="currentIndex === index" x-transition.opacity.duration.300ms class="absolute w-full h-full object-contain">
                </template>

                <div class="absolute top-[80px] left-[30px] font-mono text-white text-xl drop-shadow-[0_2px_4px_rgba(0,0,0,1)] flex flex-col gap-1">
                    <div class="font-black" x-text="images[currentIndex]?.time"></div>
                    <div class="text-red-500 font-bold flex items-center gap-2" x-text="images[currentIndex]?.status"></div>
                </div>

                <button @click.stop="prevSlide()" class="absolute left-8 p-4 bg-white/10 hover:bg-white/30 text-white rounded-full transition backdrop-blur"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg></button>
                <button @click.stop="nextSlide()" class="absolute right-8 p-4 bg-white/10 hover:bg-white/30 text-white rounded-full transition backdrop-blur"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg></button>
            </div>

            <div class="theater-footer">
                <div class="flex items-center gap-4">
                    <button @click.stop="togglePlay()" class="flex items-center gap-3 px-8 py-3 rounded-full font-black text-sm transition shadow-2xl" :class="isPlaying ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-blue-600 hover:bg-blue-700 text-white'">
                        <template x-if="isPlaying"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg></template>
                        <template x-if="!isPlaying"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></template>
                        <span x-text="isPlaying ? 'PAUSE RECORDING' : 'PLAY RECORDING'"></span>
                    </button>
                </div>
                <div class="text-slate-400 font-mono text-sm">
                    ITSM STACK - ENTERPRISE SURVEILLANCE
                </div>
            </div>

        </div>

    </div>
</x-filament-panels::page>
