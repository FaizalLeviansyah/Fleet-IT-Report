<div class="flex items-center mr-1" style="gap: 8px;" x-data="{ openPreferences: false, isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">

    <div class="relative" style="display: flex; align-items: center;">
        <button
            x-ref="prefsBtn"
            @click="openPreferences = !openPreferences"
            @click.away="openPreferences = false"
            style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: #ffffff; border: 1px solid #E5E7EB; color: #6B7280; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);"
            title="Display Preferences"
        >
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
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

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" style="display: flex; align-items: center; margin: 0;">
        @csrf
        <button type="button"
            onclick="Swal.fire({ title: 'Shutdown System?', text: 'Sesi Anda akan segera diakhiri.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Shutdown!', cancelButtonText: 'Batal', background: 'rgba(255,255,255,0.95)', backdrop: 'rgba(15,23,42,0.7)' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"
            style="display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background-color: #fef2f2; border: 1px solid #fca5a5; color: #ef4444; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" title="Shutdown / Logout">

            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path>
            </svg>
        </button>
    </form>
</div>
