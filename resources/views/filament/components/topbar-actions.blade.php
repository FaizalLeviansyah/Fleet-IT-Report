<div class="flex items-center mr-1" style="gap: 10px;" x-data="{ isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">

    <button type="button"
        x-data="{ theme: localStorage.getItem('theme') || 'light' }"
        @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.classList.toggle('dark', theme === 'dark'); $dispatch('theme-changed', theme);"
        style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #93C5FD; cursor: pointer; transition: all 0.2s;"
        onmouseover="this.style.backgroundColor='rgba(255,255,255,0.15)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='rgba(255,255,255,0.05)'; this.style.color='#93C5FD';" title="Toggle Mode">
        <svg x-show="theme === 'light'" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg x-show="theme === 'dark'" style="width: 16px; height: 16px; color: #FBBF24;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </button>

    <button type="button"
        @click="isCompact = !isCompact; if(isCompact) { document.body.classList.add('is-compact'); localStorage.setItem('isCompact', 'true'); } else { document.body.classList.remove('is-compact'); localStorage.setItem('isCompact', 'false'); }"
        style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; border: 1px solid; cursor: pointer; transition: all 0.2s;"
        :style="isCompact ? 'background-color: rgba(34, 211, 238, 0.2); border-color: #22D3EE; color: #22D3EE;' : 'background-color: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #93C5FD;'"
        onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';" title="Toggle Compact Mode">
        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
    </button>

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" style="display: flex; align-items: center; margin: 0;">
        @csrf
        <button type="button"
            onclick="Swal.fire({ title: 'Shutdown System?', text: 'Sesi Anda akan segera diakhiri.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Shutdown!' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"
            style="display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.4); color: #FCA5A5; cursor: pointer; transition: all 0.2s;"
            onmouseover="this.style.backgroundColor='rgba(239, 68, 68, 0.2)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='rgba(239, 68, 68, 0.1)'; this.style.color='#FCA5A5';" title="Shutdown / Logout">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path></svg>
        </button>
    </form>
</div>