<div style="padding: 15px 15px 5px 15px;" x-data="{ search: '' }">
    <div style="position: relative; display: flex; align-items: center; width: 100%;">

        <div style="position: absolute; left: 12px; display: flex; align-items: center; justify-content: center; pointer-events: none;">
            <svg style="width: 16px; height: 16px; color: #60A5FA;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
            placeholder="Find the menu.."
            style="width: 100%; background-color: #06285c; border: 1px solid #1e3a8a; border-radius: 8px; padding: 10px 10px 10px 36px; color: #ffffff; font-size: 13px; font-family: 'Poppins', sans-serif; outline: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);"
            onfocus="this.style.borderColor='#3b82f6'; this.style.backgroundColor='#031E49'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.2)';"
            onblur="this.style.borderColor='#1e3a8a'; this.style.backgroundColor='#06285c'; this.style.boxShadow='inset 0 2px 4px rgba(0,0,0,0.1)';"
        >

    </div>
</div>
