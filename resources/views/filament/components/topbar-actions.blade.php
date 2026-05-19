<div class="flex items-center mr-1" x-data="{ openPreferences: false, isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">
    
    <div class="relative">
        <button 
            x-ref="prefsBtn"
            @click="openPreferences = !openPreferences" 
            @click.away="openPreferences = false"
            class="flex items-center justify-center w-[42px] h-[42px] rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-700"
            title="Display Preferences"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
        </button>

        <div 
            x-show="openPreferences"
            x-anchor.bottom-end.offset.10="$refs.prefsBtn"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute z-50 w-72 bg-white dark:bg-gray-900 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-800 p-5"
            style="display: none;"
        >
            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">Display Preferences</h4>
            
            <label class="flex items-center justify-between cursor-pointer mb-5">
                <div>
                    <span class="block text-sm font-bold text-gray-700 dark:text-gray-200">Compact Mode</span>
                    <span class="block text-[11px] text-gray-500 mt-0.5">Kurangi spasi tabel & padding</span>
                </div>
                <div class="relative">
                    <input type="checkbox" class="sr-only" x-model="isCompact" @change="
                        if(isCompact) { 
                            document.body.classList.add('is-compact'); 
                            localStorage.setItem('isCompact', 'true');
                        } else { 
                            document.body.classList.remove('is-compact'); 
                            localStorage.setItem('isCompact', 'false');
                        }
                    ">
                    <div class="block bg-gray-200 dark:bg-gray-700 w-10 h-6 rounded-full transition-colors" :class="{ 'bg-blue-600': isCompact }"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="{ 'translate-x-4': isCompact }"></div>
                </div>
            </label>
        </div>
    </div>
</div>