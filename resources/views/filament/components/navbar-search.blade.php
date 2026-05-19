<div class="flex items-center gap-x-2 mr-auto pl-1">

    <a href="/admin/incident-reports/create"
       class="flex items-center justify-center w-7 h-7 rounded-full shadow-sm hover:shadow-md hover:shadow-blue-500/50 transition-all duration-300 hover:scale-110 bg-blue-600 border border-blue-500"
       title="Buat Tiket Insiden Baru">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
    </a>

    <button @click="$dispatch('open-modal', { id: 'global-search-modal' })"
            class="group flex items-center justify-between w-48 h-7 px-2.5 bg-white dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-full text-gray-400 hover:text-blue-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-gray-700 shadow-sm transition-all duration-300">
        <div class="flex items-center gap-1.5">
            <svg class="w-3 h-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span class="text-[11px] font-semibold tracking-wide">Search...</span>
        </div>
        <kbd class="hidden sm:flex items-center justify-center h-4 px-1 text-[8px] font-bold text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded">Ctrl+K</kbd>
    </button>
</div>
