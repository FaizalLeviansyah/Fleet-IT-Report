@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-20">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 animate-fade-in-up gap-4">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-white rounded-xl border-2 border-slate-300 shadow-sm text-blue-600">
                @php
                    $currentCat = $categories->where('name', $selectedCategory)->first();
                    $currentIcon = $currentCat->icon ?? 'fa-box';
                @endphp
                <i class="fa-solid {{ $currentIcon }} text-xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ strtoupper($selectedCategory) }}</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-1">Management & Configuration Database</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                <div class="text-xs font-black text-slate-700 uppercase">Online: <span class="text-emerald-600">{{ $assets->filter(fn($a) => $a->last_seen && \Carbon\Carbon::parse($a->last_seen)->diffInHours(now()) <= 2)->count() }}</span></div>
            </div>
            <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <div class="text-xs font-black text-slate-700 uppercase">Offline: <span class="text-red-600">{{ $assets->filter(fn($a) => !$a->last_seen || \Carbon\Carbon::parse($a->last_seen)->diffInHours(now()) > 2)->count() }}</span></div>
            </div>

            @if($selectedCategory !== 'Computers')
            <button onclick="openFormModal()" class="px-5 py-2 bg-blue-600 text-white border-2 border-blue-800 rounded-xl text-[11px] font-black uppercase hover:bg-blue-700 hover:scale-105 transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add {{ $selectedCategory }}
            </button>
            @endif
        </div>
    </div>

    <div class="bg-white border-2 border-slate-300 rounded-2xl shadow-sm hover:shadow-lg transition-shadow overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="overflow-x-auto custom-scrollbar min-h-[400px]">
            <table class="w-full text-xs text-left whitespace-nowrap border-0">
                <thead class="text-[10px] text-slate-800 uppercase bg-slate-200 border-b-2 border-slate-400">
                    <tr>
                        <th class="px-6 py-4 font-black">Name / Status</th>
                        <th class="px-6 py-4 font-black">Network / IP</th>
                        <th class="px-6 py-4 font-black">Manufacturer & Model</th>
                        <th class="px-6 py-4 font-black">Serial Number</th>
                        <th class="px-6 py-4 font-black">Location</th>
                        <th class="px-6 py-4 font-black">Spesifikasi / Detail</th>
                        <th class="px-6 py-4 font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($assets as $asset)
                        @php
                            $isOnline = $asset->last_seen && \Carbon\Carbon::parse($asset->last_seen)->diffInHours(now()) <= 2;
                        @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-black text-slate-900 text-sm">{{ $asset->asset_name }}</div>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($selectedCategory === 'Computers')
                                    <div class="w-2 h-2 rounded-full {{ $isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></div>
                                    <span class="text-[9px] font-bold {{ $isOnline ? 'text-emerald-600' : 'text-red-500' }} uppercase tracking-widest">{{ $isOnline ? 'ONLINE' : 'OFFLINE' }}</span>
                                    <span class="text-[9px] font-bold text-slate-400">| User: {{ $asset->current_user ?: 'Unknown' }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[9px] font-bold text-slate-600 uppercase">{{ $asset->status }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $asset->ip_address ?: '-' }}</div>
                            <div class="text-[9px] text-slate-500 font-bold uppercase mt-1">MAC: {{ $asset->mac_address ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700">{{ $asset->manufacturer ?: '-' }}</div>
                            <div class="text-[10px] text-slate-500">{{ $asset->model ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600 font-bold">
                            {{ $asset->serial_number ?: '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 border border-slate-200 rounded text-[10px] font-black text-slate-600 uppercase italic">
                                <i class="fa-solid fa-location-dot mr-1"></i> {{ $asset->location->name ?? 'Unassigned' }}
                            </span>
                            <div class="text-[9px] text-slate-500 font-bold mt-1">PIC: {{ $asset->contact_person ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($selectedCategory === 'Computers')
                                <div class="font-bold text-slate-800 text-[11px] truncate w-48" title="{{ $asset->cpu_model }}"><i class="fa-solid fa-microchip w-4 text-slate-400"></i> {{ $asset->cpu_model ?: '-' }}</div>
                                <div class="text-[10px] text-blue-600 font-black bg-blue-50 inline-block px-1.5 py-0.5 rounded border border-blue-200 mt-1"><i class="fa-solid fa-memory w-3"></i> RAM: {{ $asset->total_ram ?: '-' }}</div>
                            @else
                                <div class="font-bold text-slate-500 text-[10px] italic">Non-computational device</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openFormModal({{ $asset->toJson() }})" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-blue-600 hover:border-blue-300 shadow-sm transition-all" title="Edit Asset"><i class="fa-solid fa-pen-to-square"></i></button>

                                @if($selectedCategory === 'Computers')
                                <button onclick="openAssetModal({{ $asset->toJson() }})" class="px-3 py-2 bg-slate-800 text-white border border-slate-700 hover:bg-slate-700 rounded-lg font-bold transition-all shadow-sm text-[10px] uppercase tracking-widest flex items-center gap-2">
                                    <i class="fa-solid fa-eye"></i> X-Ray
                                </button>
                                @endif

                                <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus aset ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-500 hover:text-red-600 hover:border-red-300 shadow-sm transition-all" title="Hapus Asset"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="text-slate-400 mb-2"><i class="fa-solid {{ $currentIcon }} text-4xl"></i></div>
                            <div class="text-slate-500 font-bold text-sm">Belum ada perangkat dalam kategori {{ $selectedCategory }}.</div>
                            @if($selectedCategory === 'Computers')
                                <div class="text-xs text-slate-400 mt-1">Data akan muncul otomatis saat Amarin Sentinel Agent terhubung.</div>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="formModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 z-[110] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="relative w-full max-w-4xl max-h-[95vh] mx-auto flex flex-col bg-white rounded-2xl shadow-2xl border-2 border-slate-300 animate-fade-in-up">

        <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 rounded-t-2xl shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200">
                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900" id="modal-form-title">Edit Data {{ $selectedCategory }}</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Manajemen Data Administratif Aset</p>
                </div>
            </div>
            <button type="button" onclick="closeFormModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 flex justify-center items-center border border-slate-200 shadow-sm">
                <svg class="w-3 h-3 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-[#f4f7fb] custom-scrollbar">
            <form id="crudForm" method="POST" action="{{ route('assets.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <input type="hidden" name="category_id" id="input_category_id" value="{{ $currentCat->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4 border-t-4 border-t-blue-500">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-100 pb-2">1. Identitas Aset & Audit</h4>

                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                            <label class="block text-[11px] font-black text-blue-800 uppercase tracking-wider mb-1">Nama Aset (Hostname) <span class="text-red-500">*</span></label>
                            <input type="text" name="asset_name" id="input_asset_name" required class="w-full text-sm rounded-lg border-blue-300 focus:border-blue-600 focus:ring-blue-500 font-bold text-blue-900" placeholder="Misal: ASMNB0001">
                            <p class="text-[9px] text-blue-600 font-bold mt-1.5 leading-tight"><i class="fa-solid fa-circle-info"></i> <b>Audit Rule:</b> Jika terjadi pergantian PIC, tambahkan nama baru di belakang nama lama (Contoh: <i>ASMNB0001 - Bimo/Dika</i>) untuk menjaga histori kepemilikan.</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Status Aset <span class="text-red-500">*</span></label>
                            <select name="status" id="input_status" required class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 font-bold">
                                <option value="Active">Active / In Use</option>
                                <option value="Stock">In Stock (Gudang)</option>
                                <option value="Maintenance">Under Maintenance</option>
                                <option value="Broken">Broken / Rusak</option>
                                <option value="Disposed">Disposed (Dibuang)</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Pabrikan</label>
                                <input type="text" name="manufacturer" id="input_manufacturer" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 font-bold" placeholder="Lenovo, HP...">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Model / Tipe</label>
                                <input type="text" name="model" id="input_model" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 font-bold" placeholder="ThinkPad T14...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Serial Number (SN)</label>
                            <input type="text" name="serial_number" id="input_serial_number" class="w-full text-sm rounded-lg border-slate-300 font-mono text-slate-600 focus:border-blue-500" placeholder="Biarkan kosong jika PC (Auto-Sync)">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4 border-t-4 border-t-emerald-500">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-100 pb-2">2. Alokasi Lokasi</h4>

                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Penempatan (Kapal / Unit) <span class="text-red-500">*</span></label>
                                <select name="vessel_id" id="input_vessel_id" required class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold">
                                    <option value="">-- Pilih Armada --</option>
                                    @foreach($vessels as $vessel)
                                        <option value="{{ $vessel->id }}">{{ $vessel->vessel_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Departemen / Grup</label>
                                    <input type="text" name="group_name" id="input_group_name" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold" placeholder="Deck / Office...">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">PIC / Pengguna</label>
                                    <input type="text" name="contact_person" id="input_contact_person" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold" placeholder="Nama Penanggung Jawab">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4 border-t-4 border-t-purple-500">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-100 pb-2">3. Jaringan Dasar</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">IP Address</label>
                                    <input type="text" name="ip_address" id="input_ip_address" class="w-full text-sm rounded-lg border-slate-300 focus:border-purple-500 font-mono text-slate-600" placeholder="192.168.x.x">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">MAC Address</label>
                                    <input type="text" name="mac_address" id="input_mac_address" class="w-full text-sm rounded-lg border-slate-300 focus:border-purple-500 font-mono text-slate-600" placeholder="00:1A:2B:...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="flex items-center justify-end gap-3 p-4 border-t-2 border-slate-200 bg-slate-50 rounded-b-2xl shrink-0">
            <button type="button" onclick="closeFormModal()" class="px-6 py-2.5 bg-white text-slate-600 border-2 border-slate-300 hover:bg-slate-100 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-sm">Batal</button>
            <button type="submit" form="crudForm" class="px-6 py-2.5 bg-blue-600 text-white border-2 border-blue-800 hover:bg-blue-700 hover:scale-105 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-md flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Aset
            </button>
        </div>
    </div>
</div>

<script>
    // Script untuk membuka form mode Create / Edit
    function openFormModal(assetData = null) {
        const modal = document.getElementById('formModal');
        const form = document.getElementById('crudForm');
        const title = document.getElementById('modal-form-title');
        const methodInput = document.getElementById('form-method');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if(assetData) {
            // MODE EDIT
            title.innerText = `Edit Data {{ $selectedCategory }}`;
            form.action = `/assets/${assetData.id}`;
            methodInput.value = 'PUT';

            // Populate data
            document.getElementById('input_asset_name').value = assetData.asset_name || '';
            document.getElementById('input_status').value = assetData.status || 'Active';
            document.getElementById('input_manufacturer').value = assetData.manufacturer || '';
            document.getElementById('input_model').value = assetData.model || '';
            document.getElementById('input_serial_number').value = assetData.serial_number || '';
            document.getElementById('input_vessel_id').value = assetData.vessel_id || '';
            document.getElementById('input_group_name').value = assetData.group_name || '';
            document.getElementById('input_contact_person').value = assetData.contact_person || '';
            document.getElementById('input_ip_address').value = assetData.ip_address || '';
            document.getElementById('input_mac_address').value = assetData.mac_address || '';
        } else {
            // MODE CREATE (Hanya berlaku untuk non-Computers)
            title.innerText = `Tambah {{ $selectedCategory }} Baru`;
            form.action = "{{ route('assets.store') }}";
            methodInput.value = 'POST';
            form.reset();
        }
    }

    function closeFormModal() {
        document.getElementById('formModal').classList.add('hidden');
        document.getElementById('formModal').classList.remove('flex');
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
