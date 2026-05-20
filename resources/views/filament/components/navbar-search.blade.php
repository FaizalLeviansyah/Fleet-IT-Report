<div class="flex items-center mr-auto pl-1" style="gap: 8px;">

    <div x-data="{ isHovered: false }" style="display: flex; align-items: center;">
        <a href="javascript:void(0)"
           @mouseenter="isHovered = true"
           @mouseleave="isHovered = false"
           onclick="Swal.fire({ title: 'Buat Tiket Laporan?', text: 'Anda akan dialihkan ke halaman Formulir Pelaporan Insiden ITSM.', icon: 'info', showCancelButton: true, confirmButtonColor: '#2563EB', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Buat Tiket', cancelButtonText: 'Batal', background: 'rgba(255,255,255,0.95)', backdrop: 'rgba(15,23,42,0.7)' }).then((result) => { if(result.isConfirmed) window.location.href='/admin/incident-reports/create' })"
           style="display: flex; align-items: center; height: 28px; border-radius: 9999px; background-color: #2563EB; border: 1px solid #1D4ED8; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); overflow: hidden; box-shadow: 0 2px 4px rgba(37,99,235,0.3); text-decoration: none;"
           :style="isHovered ? 'width: 110px; justify-content: flex-start; padding-left: 8px;' : 'width: 28px; justify-content: center; padding: 0;'">

            <svg style="width: 14px; height: 14px; color: #ffffff; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>

            <span style="color: #ffffff; font-size: 10px; font-weight: bold; margin-left: 6px; white-space: nowrap; transition: opacity 0.2s ease-in-out;"
                  :style="isHovered ? 'opacity: 1;' : 'opacity: 0;'">
                Create Ticket
            </span>
        </a>
    </div>

    <button @click="$dispatch('open-modal', { id: 'global-search-modal' })"
            style="display: flex; align-items: center; justify-content: space-between; width: 192px; height: 28px; padding: 0 10px; background-color: rgba(255,255,255,0.9); border: 1px solid #E5E7EB; border-radius: 9999px; cursor: pointer; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
        <div style="display: flex; align-items: center; gap: 6px;">
            <svg style="width: 12px; height: 12px; color: #9CA3AF;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <span style="font-size: 11px; font-weight: 600; color: #9CA3AF;">Search...</span>
        </div>
        <kbd style="display: flex; align-items: center; justify-content: center; height: 16px; padding: 0 4px; font-size: 8px; font-weight: bold; color: #9CA3AF; background-color: #F3F4F6; border: 1px solid #E5E7EB; border-radius: 4px;">Ctrl+K</kbd>
    </button>
</div>
