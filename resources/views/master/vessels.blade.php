@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-6 animate-fade-in-up">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Master Data Armada</h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Pengaturan Database Kapal & PIC IT</p>
            </div>
        </div>
        <button onclick="openVesselModal('add')" class="px-6 py-2.5 bg-indigo-600 text-white font-black text-xs uppercase tracking-widest rounded-xl border-2 border-indigo-800 hover:bg-indigo-700 hover:scale-105 shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg> Tambah Kapal
        </button>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-xl shadow-sm overflow-hidden animate-fade-in-up">
        <div class="overflow-x-auto min-h-[400px] custom-scrollbar">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400">
                    <tr>
                        <th class="px-6 py-4 font-black w-10">No</th>
                        <th class="px-6 py-4 font-black">Nama Kapal</th>
                        <th class="px-6 py-4 font-black">Perusahaan (Company)</th>
                        <th class="px-6 py-4 font-black text-center">PIC IT</th>
                        <th class="px-6 py-4 font-black text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-300">
                    @foreach ($vessels as $index => $vessel)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-black text-slate-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-black text-indigo-800 text-sm">{{ $vessel->vessel_name }}</td>
                        <td class="px-6 py-4 font-bold text-slate-600 uppercase text-[10px]">{{ $vessel->company_name }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $isLevi = str_contains(strtolower($vessel->pic_name), 'levi');
                                $badgeClass = $isLevi ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-emerald-100 text-emerald-800 border-emerald-300';
                            @endphp
                            <span class="px-2.5 py-1.5 rounded text-[10px] font-black border-2 shadow-sm {{ $badgeClass }}">
                                {{ strtoupper($vessel->pic_name) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button onclick="openVesselModal('edit', {{ $vessel }})" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 border-2 border-amber-200 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('master.vessels.destroy', $vessel->id) }}" method="POST" class="inline-block" onsubmit="return confirmDelete(event)">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-50 text-red-600 hover:bg-red-100 border-2 border-red-200 rounded-lg transition-colors" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="vesselModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-screen bg-slate-900/60 backdrop-blur-md">
    <div class="relative p-4 w-full max-w-lg">
        <div class="relative bg-white rounded-2xl shadow-2xl border-2 border-slate-300">
            <div class="flex items-center justify-between p-4 border-b-2 border-slate-200 bg-slate-50 rounded-t-xl">
                <h3 class="text-lg font-black text-slate-900" id="modal-title">Tambah Kapal Baru</h3>
                <button type="button" onclick="closeVesselModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 flex justify-center items-center border border-slate-200"><svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <div class="p-6">
                <form id="vesselForm" method="POST" action="{{ route('master.vessels.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Nama Kapal</label><input type="text" name="vessel_name" id="input-vessel" required class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100" placeholder="Contoh: SOVIANA"></div>
                    <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">Nama Perusahaan (Company)</label><input type="text" name="company_name" id="input-company" required class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100" placeholder="Contoh: PT Amarin Ship Management"></div>
                    <div><label class="block mb-2 text-xs font-bold text-slate-600 uppercase">PIC IT Penanggung Jawab</label><select name="pic_name" id="input-pic" required class="w-full rounded-lg border-2 border-slate-300 text-sm font-bold focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100"><option value="Levi">Levi</option><option value="Farhan">Farhan</option></select></div>
                    <div class="pt-4 border-t-2 border-slate-200 flex justify-end gap-3"><button type="button" onclick="closeVesselModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 border-2 border-slate-300 hover:bg-slate-200 rounded-xl font-black text-xs uppercase">Batal</button><button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white border-2 border-indigo-800 hover:bg-indigo-700 rounded-xl font-black text-xs uppercase">Simpan Data</button></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.body.appendChild(document.getElementById('vesselModal'));
    });

    function openVesselModal(mode, data = null) {
        const modal = document.getElementById('vesselModal');
        const form = document.getElementById('vesselForm');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if(mode === 'add') {
            document.getElementById('modal-title').innerText = 'Tambah Kapal Baru';
            form.action = "{{ route('master.vessels.store') }}";
            document.getElementById('form-method').value = 'POST';
            form.reset();
        } else {
            document.getElementById('modal-title').innerText = 'Edit Data Kapal';
            form.action = `/master/vessels/${data.id}`;
            document.getElementById('form-method').value = 'PUT';
            document.getElementById('input-vessel').value = data.vessel_name;
            document.getElementById('input-company').value = data.company_name;
            document.getElementById('input-pic').value = data.pic_name;
        }
    }

    function closeVesselModal() {
        document.getElementById('vesselModal').classList.add('hidden');
        document.getElementById('vesselModal').classList.remove('flex');
    }

    function confirmDelete(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Hapus Kapal?', text: "Data tidak bisa dikembalikan!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus!'
        }).then((result) => { if(result.isConfirmed) e.target.submit(); });
    }
</script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection
