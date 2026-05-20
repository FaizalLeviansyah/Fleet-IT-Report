<div class="flex items-center mr-1" style="gap: 8px;" x-data="{ isCompact: localStorage.getItem('isCompact') === 'true' }" x-init="if(isCompact) document.body.classList.add('is-compact')">

    <button type="button"
        x-data="{ theme: localStorage.getItem('theme') || 'light' }"
        @click="theme = theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', theme); document.documentElement.classList.toggle('dark', theme === 'dark'); $dispatch('theme-changed', theme);"
        style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background-color: #ffffff; border: 1px solid #E5E7EB; color: #6B7280; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
        onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';" title="Toggle Dark/Light Mode">
        <svg x-show="theme === 'light'" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        <svg x-show="theme === 'dark'" style="width: 14px; height: 14px; color: #FBBF24;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </button>

    <button type="button"
        @click="isCompact = !isCompact; if(isCompact) { document.body.classList.add('is-compact'); localStorage.setItem('isCompact', 'true'); Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, icon: 'success', title: 'Compact Mode Active!' }); } else { document.body.classList.remove('is-compact'); localStorage.setItem('isCompact', 'false'); Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, icon: 'info', title: 'Default Mode Active!' }); }"
        style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background-color: #ffffff; border: 1px solid #E5E7EB; color: #6B7280; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
        :style="isCompact ? 'background-color: #EEF2FF; border-color: #C7D2FE; color: #4F46E5;' : ''"
        onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';" title="Toggle Compact Mode">
        <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
    </button>

    <form method="POST" action="{{ route('filament.admin.auth.logout') }}" style="display: flex; align-items: center; margin: 0;">
        @csrf
        <button type="button"
            onclick="Swal.fire({ title: 'Shutdown System?', text: 'Sesi Anda akan segera diakhiri.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Shutdown!' }).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })"
            style="display: flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 50%; background-color: #fef2f2; border: 1px solid #fca5a5; color: #ef4444; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;"
            onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';" title="Shutdown / Logout">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path></svg>
        </button>
    </form>
</div>
