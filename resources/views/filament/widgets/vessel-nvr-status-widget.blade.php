<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                Live Radar: NVR & CCTV Kapal
            </h2>
            <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full border border-gray-200 dark:border-gray-700">Auto-sync 15s</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse ($vessels as $vessel)
                <div class="relative flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-800/50 border {{ $vessel['border'] }} rounded-xl shadow-sm hover:shadow-md transition-shadow">

                    <span class="absolute top-3 right-3 flex h-3 w-3">
                        @if($vessel['pulse'])
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $vessel['color'] }}"></span>
                        @endif
                        <span class="relative inline-flex rounded-full h-3 w-3 {{ $vessel['color'] }}"></span>
                    </span>

                    <svg class="w-8 h-8 mb-2 {{ $vessel['text'] }} opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>

                    <span class="text-[13px] font-bold text-gray-800 dark:text-gray-200 text-center truncate w-full">{{ $vessel['name'] }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $vessel['text'] }} mt-1">{{ $vessel['status'] }}</span>
                </div>
            @empty
                <div class="col-span-full p-6 text-center text-gray-500 dark:text-gray-400 text-sm border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                    Belum ada data Master Kapal terdaftar.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
