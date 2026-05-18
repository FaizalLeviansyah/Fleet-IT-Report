<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @for ($i = 1; $i <= 6; $i++)
            <div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700">
                <div class="bg-black aspect-video flex items-center justify-center relative">
                    <span class="text-red-500 font-bold animate-pulse absolute top-2 right-2">● REC</span>
                    <span class="text-gray-400 font-medium">Kamera 0{{ $i }} - Menunggu Sinyal...</span>
                </div>
                <div class="p-3 bg-gray-900 text-white text-sm font-semibold flex justify-between">
                    <span>CH-0{{ $i }}</span>
                    <span class="text-green-400">Online</span>
                </div>
            </div>
        @endfor
    </div>
</x-filament-panels::page>
