<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    📡 Live Radar Monitoring Armada
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pemantauan titik lokasi kapal secara real-time (Simulasi)</p>
            </div>
            <span class="flex h-3 w-3 relative" title="Sistem Online">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
        </div>

        <div class="relative w-full h-80 bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center border border-slate-700 shadow-inner">
            <div class="absolute w-[28rem] h-[28rem] border border-green-500/20 rounded-full"></div>
            <div class="absolute w-80 h-80 border border-green-500/30 rounded-full"></div>
            <div class="absolute w-48 h-48 border border-green-500/40 rounded-full"></div>
            <div class="absolute w-16 h-16 border border-green-500/50 rounded-full"></div>

            <div class="absolute w-full h-px bg-green-500/30"></div>
            <div class="absolute h-full w-px bg-green-500/30"></div>

            <div class="absolute w-40 h-40 bg-gradient-to-tr from-green-500/50 to-transparent rounded-tl-full origin-bottom-right animate-[spin_3s_linear_infinite]" style="top: calc(50% - 10rem); left: calc(50% - 10rem);"></div>

            <div class="absolute w-2.5 h-2.5 bg-green-400 rounded-full shadow-[0_0_10px_#4ade80]" style="top: 30%; left: 65%;" title="MT Amarin 08 - Status Aman"></div>
            <div class="absolute w-2 h-2 bg-green-400 rounded-full shadow-[0_0_10px_#4ade80]" style="top: 70%; left: 25%;" title="MT Soviana - Status Aman"></div>

            <div class="absolute w-3 h-3 bg-red-500 rounded-full shadow-[0_0_15px_#ef4444] animate-pulse" style="top: 45%; left: 35%;" title="Kapal X - Terdeteksi Insiden!">
                <div class="absolute -top-6 -left-8 bg-red-600 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow-lg whitespace-nowrap">Warning: ITSM!</div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
