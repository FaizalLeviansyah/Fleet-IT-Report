<div class="px-2 pb-2 pt-2" x-data="{ search: '' }">
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input
            type="text"
            x-model="search"
            x-on:keyup="
                let term = search.toLowerCase();
                document.querySelectorAll('.fi-sidebar-item').forEach(item => {
                    let text = item.textContent.toLowerCase();
                    item.style.display = text.includes(term) ? '' : 'none';
                });
                document.querySelectorAll('.fi-sidebar-group').forEach(group => {
                    let items = Array.from(group.querySelectorAll('.fi-sidebar-item'));
                    let hasVisible = items.some(item => item.style.display !== 'none');
                    group.style.display = hasVisible ? '' : 'none';
                });
            "
            placeholder="Search menu..."
            class="block w-full rounded-lg border-0 py-2 pl-5 text-sm text-gray-900 bg-white/10 text-white placeholder:text-gray-300 focus:bg-white focus:text-gray-900 focus:ring-2 focus:ring-white transition-colors"
        >
    </div>
</div>
