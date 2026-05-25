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
        .f-input { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; background: transparent; width: 100%; transition: 0.2s;}
        .dark .f-input { border-color: #475569; color: white; }
        .f-input:focus { border-color: #2563EB; outline: none; }

        .btn-apply { background: #2563EB; color: white; padding: 10px 25px; border-radius: 6px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;}
        .btn-apply:hover { background: #1D4ED8; }

        .monitor-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
        @media (min-width: 768px) { .monitor-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1280px) { .monitor-grid { grid-template-columns: repeat(3, 1fr); } }

        /* Card Animasi Hover Keren */
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
        .snap-img { width: 100%; height: 100%; object-fit: cover; }
        .play-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; }
        .cam-card:hover .play-overlay { opacity: 1; }
        .play-overlay svg { width: 48px; height: 48px; color: white; drop-shadow: 0 4px 6px rgba(0,0,0,0.5); }

        .cam-footer { padding: 12px 15px; background: #fafafa; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 8px; }
        .dark .cam-footer { background: #1E293B; border-color: #334155; }
        .footer-row { display: flex; justify-content: space-between; align-items: center; font-size: 11px; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-value { font-weight: 800; color: #2563EB; }
        .dark .info-value { color: #38BDF8; }
        .signal-good { color: #10b981; } .signal-delay { color: #f59e0b; }
    </style>

    <div x-data="{
        isModalOpen: false,
        activeChannel: '',
        images: [],
        currentIndex: 0,
        interval: null,
        isPlaying: true,

        openTheater(channelName, dataImages) {
            if(dataImages.length === 0) {
                new FilamentNotification().title('Kamera Offline').body('Tidak ada foto untuk rentang waktu ini.').danger().send();
                return;
            }
            this.activeChannel = channelName;
            this.images = dataImages;
            this.currentIndex = 0;
            this.isModalOpen = true;
            this.startSlideshow();
            new FilamentNotification().title('Theater Mode').body('Membuka ' + channelName).success().send();
        },
        closeTheater() {
            this.isModalOpen = false;
            clearInterval(this.interval);
        },
        startSlideshow() {
            this.isPlaying = true;
            clearInterval(this.interval);
            if(this.images.length > 1) {
                this.interval = setInterval(() => { this.nextSlide(); }, 5000); // 5 Detik per slide
            }
        },
        pauseSlideshow() {
            this.isPlaying = false;
            clearInterval(this.interval);
        },
        togglePlay() {
            if(this.isPlaying) {
                this.pauseSlideshow();
                new FilamentNotification().title('Slideshow Dijeda').warning().send();
            } else {
                this.startSlideshow();
                new FilamentNotification().title('Slideshow Dimulai').success().send();
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
                <div><div class="stat-label">CCTV Online Status</div><div class="stat-value">{{ $total_active_cams }} / {{ count($channels) }} Kamera Aktif</div></div>
            </div>
        </div>

        <div class="glass-card">
            <h5 class="mb-4 font-bold text-slate-800 dark:text-slate-200">Monitoring Filter</h5>
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
                <div class="f-group"><label class="f-label">Start Date</label><input type="date" wire:model="start_date" class="f-input" required></div>
                <div class="f-group"><label class="f-label">End Date</label><input type="date" wire:model="end_date" class="f-input" required></div>
                <div class="f-group"><label class="f-label">Start Time</label><input type="time" wire:model="start_time" class="f-input"></div>
                <div class="f-group"><label class="f-label">End Time</label><input type="time" wire:model="end_time" class="f-input"></div>
                <button type="submit" class="btn-apply">TAMPILKAN</button>
            </form>
        </div>

        <div class="monitor-grid">
            @foreach($channels as $ch)
                @php
                    $images = $data_per_channel[$ch] ?? collect();
                    $totalImages = count($images);
                    $fullName = $channel_labels[$ch] ?? $ch;

                    // Kita format JSON Array yang ringan untuk dikirim ke Alpine.js
                    $alpineImages = $images->map(function($img) {
                        $delay = \Carbon\Carbon::parse($img->created_at)->diffInMinutes(\Carbon\Carbon::parse($img->captured_at));
                        return [
                            'url' => asset('storage/' . $img->image_path),
                            'time' => \Carbon\Carbon::parse($img->captured_at)->format('d M Y - H:i:s'),
                            'status' => $delay < 60 ? '🟢 Sinyal Stabil' : "🟠 Delay ({$delay}m)"
                        ];
                    })->values()->toJson();
                @endphp

                <div class="cam-card" @click="openTheater('{{ $fullName }}', {{ $alpineImages }})">
                    <div class="cam-header">
                        <div class="cam-title">{{ $fullName }}</div>
                        <div class="status-live">
                            <span class="inline-block w-2 h-2 rounded-full {{ $totalImages > 0 ? 'bg-green-500 animate-pulse' : 'bg-slate-300 dark:bg-slate-600' }}"></span>
                            {{ $totalImages > 0 ? 'LIVE' : 'OFFLINE' }}
                        </div>
                    </div>

                    <div class="img-box">
                        @if($totalImages > 0)
                            <img src="{{ asset('storage/' . $images->last()->image_path) }}" class="snap-img active" alt="{{ $fullName }}">
                            <div class="play-overlay">
                                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        @else
                            <div class="text-slate-500 text-[11px] font-bold tracking-widest text-center">NO CONNECTION</div>
                        @endif
                    </div>

                    <div class="cam-footer">
                        <div class="footer-row border-b border-gray-100 dark:border-slate-700 pb-1.5">
                            <div class="info-label">Latest Snapshot:</div>
                            <div class="info-value">
                                {{ $totalImages > 0 ? \Carbon\Carbon::parse($images->last()->captured_at)->format('d-m-Y H:i') : '-' }}
                            </div>
                        </div>
                        <div class="footer-row">
                            <div class="info-label">Total Frame / Hari Ini:</div>
                            <div class="info-value">{{ $totalImages }} Foto</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-[99] flex items-center justify-center bg-black/90 backdrop-blur-sm" x-transition.opacity>
            <div class="bg-slate-900 rounded-xl overflow-hidden shadow-2xl w-full max-w-5xl mx-4 border border-slate-700 flex flex-col" @click.away="closeTheater()">

                <div class="px-6 py-4 flex justify-between items-center bg-slate-800 border-b border-slate-700">
                    <h3 class="text-white font-bold text-lg flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                        <span x-text="activeChannel"></span>
                        <span class="text-slate-400 text-sm ml-2" x-text="'(' + images.length + ' Frame)'"></span>
                    </h3>
                    <button @click="closeTheater()" class="text-slate-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="relative bg-black flex items-center justify-center" style="height: 65vh;">
                    <template x-for="(img, index) in images" :key="index">
                        <img :src="img.url" x-show="currentIndex === index" x-transition.opacity.duration.300ms class="absolute w-full h-full object-contain">
                    </template>

                    <div class="absolute top-4 left-4 bg-black/60 text-white font-mono text-sm px-3 py-1 rounded">
                        <span x-text="images[currentIndex]?.time"></span> |
                        <span x-text="images[currentIndex]?.status" class="ml-2 font-bold text-green-400"></span>
                    </div>

                    <button @click.stop="prevSlide()" class="absolute left-4 p-3 bg-black/50 text-white hover:bg-black/80 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                    <button @click.stop="nextSlide()" class="absolute right-4 p-3 bg-black/50 text-white hover:bg-black/80 rounded-full transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                </div>

                <div class="px-6 py-4 bg-slate-800 flex justify-center items-center gap-6">
                    <button @click.stop="togglePlay()" class="flex items-center gap-2 px-6 py-2 rounded-full font-bold text-sm transition" :class="isPlaying ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white'">
                        <template x-if="isPlaying">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </template>
                        <template x-if="!isPlaying">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </template>
                        <span x-text="isPlaying ? 'PAUSE AUTO-SLIDE' : 'PLAY SLIDESHOW'"></span>
                    </button>
                    <div class="text-slate-400 text-sm">
                        Slide <span class="font-bold text-white" x-text="currentIndex + 1"></span> dari <span class="font-bold text-white" x-text="images.length"></span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-filament-panels::page>
