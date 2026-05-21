<div class="flex items-center mr-1 gap-2.5" x-data="{ isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">

    <button type="button"
        x-data="{ theme: localStorage.getItem('theme') || 'light' }"
        @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.classList.toggle('dark', theme === 'dark'); $dispatch('theme-changed', theme);"
        class="flex items-center justify-center w-[34px] h-[34px] rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition-all hover:bg-gray-50 hover:text-blue-600 hover:scale-110 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-amber-400"
        title="Toggle Theme">
        <svg x-show="theme === 'light'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg x-show="theme === 'dark'" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </button>

    <button type="button"
        @click="isCompact = !isCompact; if(isCompact) { document.body.classList.add('is-compact'); localStorage.setItem('isCompact', 'true'); } else { document.body.classList.remove('is-compact'); localStorage.setItem('isCompact', 'false'); }"
        class="flex items-center justify-center w-[34px] h-[34px] rounded-full border shadow-sm transition-all hover:scale-110"
        :class="isCompact ? 'bg-blue-50 border-blue-200 text-blue-600 dark:bg-sky-900/30 dark:border-sky-700 dark:text-sky-400' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-700'"
        title="Toggle Compact Mode">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
    </button>

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="m-0 flex items-center">
        @csrf
        <button type="button"
            onclick="Swal.fire({ title: 'Shutdown System?', text: 'Sesi Anda akan diakhiri.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Shutdown!' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"
            class="flex items-center justify-center w-[34px] h-[34px] rounded-full border border-red-200 bg-red-50 text-red-500 shadow-sm transition-all hover:bg-red-100 hover:text-red-600 hover:scale-110 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 dark:hover:text-red-300"
            title="Shutdown / Logout">
            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path></svg>
        </button>
    </form>
</div>
