<div class="flex items-center gap-x-2 mr-1" x-data="{ openPreferences: false, isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">

    <div class="relative">
        <button
            x-ref="prefsBtn"
            @click="openPreferences = !openPreferences"
            @click.away="openPreferences = false"
            class="flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-indigo-600 dark:text-gray-300 shadow-sm transition-all duration-300 hover:scale-110 hover:shadow-indigo-500/30"
            title="Display Preferences"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
        </button>

        <div
            x-show="openPreferences"
            x-anchor.bottom-end.offset.12="$refs.prefsBtn"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
            class="absolute z-50 w-56 bg-white/90 backdrop-blur-md dark:bg-gray-900/90 rounded-2xl shadow-[0_15px_30px_-5px_rgba(0,0,0,0.2)] border border-gray-100 dark:border-gray-800 p-4"
            style="display: none;"
        >
            <h4 class="text-[10px] font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-cyan-500 uppercase tracking-wider mb-3">Preferences</h4>

            <label class="flex items-center justify-between cursor-pointer group">
                <div class="pr-2">
                    <span class="block text-xs font-bold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 transition-colors">Compact Mode</span>
                </div>
                <div class="relative flex-shrink-0 mt-0.5">
                    <input type="checkbox" class="sr-only" x-model="isCompact" @change="
                        isCompact ? (document.body.classList.add('is-compact'), localStorage.setItem('isCompact', 'true')) : (document.body.classList.remove('is-compact'), localStorage.setItem('isCompact', 'false'))
                    ">
                    <div class="block w-8 h-4 rounded-full shadow-inner transition-colors duration-300 ease-in-out" :class="isCompact ? 'bg-gradient-to-r from-indigo-500 to-blue-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
                    <div class="absolute left-[2px] top-[2px] bg-white w-3 h-3 rounded-full shadow-sm transition-transform duration-300 ease-out" :class="isCompact ? 'translate-x-4' : 'translate-x-0'"></div>
                </div>
            </label>
        </div>
    </div>

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="ml-0.5">
        @csrf
        <button type="button"
            onclick="Swal.fire({ title: 'Shutdown System?', text: 'Sesi Anda akan segera diakhiri.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Shutdown!', cancelButtonText: 'Batal', background: 'rgba(255,255,255,0.9)', backdrop: 'rgba(15,23,42,0.7)' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"
            class="flex items-center justify-center w-7 h-7 rounded-full bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:text-white hover:bg-gradient-to-br hover:from-red-500 hover:to-red-600 shadow-sm transition-all duration-300 hover:scale-110 hover:shadow-red-500/30" title="Shutdown / Logout">

            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 1 12.728 0M12 3v9"></path></svg>
        </button>
    </form>
</div>
