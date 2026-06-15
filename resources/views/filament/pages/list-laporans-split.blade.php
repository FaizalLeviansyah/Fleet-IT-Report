<x-filament-panels::page>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        <div class="xl:col-span-1 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-6 sticky top-6">

            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-600 p-3 rounded-xl shadow-lg shadow-blue-500/30">
                    <x-heroicon-o-document-text class="w-6 h-6 text-white"/>
                </div>
                <div>
                    <h2 class="text-xl font-black italic tracking-tight text-gray-800 dark:text-white">
                        {{ $isDuplicating ? 'DUPLICATE DATA' : 'NEW REPORT' }}
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold tracking-widest uppercase">MANUAL ENTRY LOG</p>
                </div>
            </div>

            <x-filament-panels::form wire:submit="saveReport">
                {{ $this->createReportForm }}

                <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                    COMMIT REPORT
                </button>

                @if($isDuplicating)
                    <button type="button" wire:click="cancelDuplicate" class="w-full mt-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-3 px-4 rounded-xl transition-all">
                        CANCEL DUPLICATE
                    </button>
                @endif
            </x-filament-panels::form>
        </div>

        <div class="xl:col-span-2">
            {{ $this->table }}
        </div>

    </div>
</x-filament-panels::page>
