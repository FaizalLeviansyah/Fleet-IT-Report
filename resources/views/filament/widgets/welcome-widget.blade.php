<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-4">
            <div class="h-14 w-14 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                {{ substr(auth()->user()->full_name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Selamat Datang, {{ auth()->user()->full_name }}! 👋
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Anda login sebagai <span class="font-bold text-blue-600">{{ strtoupper(auth()->user()->role) }}</span>.
                    Pantau kinerja IT, aset, dan CCTV Anda hari ini.
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
