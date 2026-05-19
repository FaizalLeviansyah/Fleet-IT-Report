<div class="flex items-center gap-x-3 mr-auto pl-2">
    <button @click="$dispatch('open-modal', { id: 'global-search-modal' })" class="flex items-center justify-between w-64 h-[20px] px-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-gray-500 hover:text-gray-700 dark:text-gray-400 shadow-sm transition-all">
        <div class="flex items-center gap-2">
            <svg class="w-2 h-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span class="text-sm font-medium">Search...</span>
        </div>
        {{-- <kbd class="hidden sm:flex items-center h-5 px-1.5 text-[5px] font-bold text-gray-400 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded">Ctrl+K</kbd> --}}
    </button>

    <div class="hidden md:flex items-center gap-x-2 bg-white dark:bg-gray-800 h-[42px] px-4 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm">
        <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
        </span>
        <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">System Online</span>
    </div>
</div>