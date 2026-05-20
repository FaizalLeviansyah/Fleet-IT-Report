<div class="flex items-center mr-3 pl-1" style="gap: 8px;">
    <div x-data="{ isHovered: false }" style="display: flex; align-items: center;">
        <a href="javascript:void(0)"
           @mouseenter="isHovered = true"
           @mouseleave="isHovered = false"
           onclick="Swal.fire({ title: 'Buat Tiket Laporan?', text: 'Anda akan dialihkan ke halaman Formulir Pelaporan Insiden ITSM.', icon: 'info', showCancelButton: true, confirmButtonColor: '#2563EB', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Buat Tiket', cancelButtonText: 'Batal', background: 'rgba(255,255,255,0.95)', backdrop: 'rgba(15,23,42,0.7)' }).then((result) => { if(result.isConfirmed) window.location.href='/admin/incident-reports/create' })"
           style="display: flex; align-items: center; height: 30px; border-radius: 9999px; background-color: #2563EB; border: 1px solid #1D4ED8; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); overflow: hidden; box-shadow: 0 2px 4px rgba(37,99,235,0.3); text-decoration: none;"
           :style="isHovered ? 'width: 110px; justify-content: flex-start; padding-left: 8px;' : 'width: 30px; justify-content: center; padding: 0;'">

            <svg style="width: 14px; height: 14px; color: #ffffff; flex-shrink: 0;" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg>
            <span style="color: #ffffff; font-size: 10px; font-weight: bold; margin-left: 6px; white-space: nowrap; transition: opacity 0.2s ease-in-out;"
                  :style="isHovered ? 'opacity: 1;' : 'opacity: 0;'">
                Create Ticket
            </span>
        </a>
    </div>
</div>
