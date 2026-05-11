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
                        <th class="px-6 py-4 font-black">Company & Placement</th>
                        <th class="px-6 py-4 font-black">Serial Number</th>
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
                            <div class="font-bold text-blue-800 text-[11px] truncate" title="{{ $asset->company_entity }}"><i class="fa-regular fa-building mr-1"></i> {{ str_replace('PT ', '', explode('(', $asset->company_entity)[0] ?? 'Unknown') }}</div>
                            <div class="mt-1">
                                @if($asset->vessel_id)
                                    <span class="px-2 py-0.5 bg-indigo-50 border border-indigo-200 rounded text-[9px] font-black text-indigo-700 uppercase"><i class="fa-solid fa-ship mr-1"></i> {{ $asset->vessel->vessel_name ?? 'Unknown Vessel' }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-slate-100 border border-slate-200 rounded text-[9px] font-black text-slate-600 uppercase"><i class="fa-solid fa-building-user mr-1"></i> Head Office</span>
                                @endif
                            </div>
                            <div class="text-[9px] text-slate-500 font-bold mt-1.5"><i class="fa-solid fa-location-dot"></i> {{ $asset->location->name ?? 'Unassigned' }} | PIC: {{ $asset->contact_person ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-600 font-bold">
                            {{ $asset->serial_number ?: '-' }}
                            <div class="text-[9px] font-sans text-slate-400 mt-1">{{ $asset->manufacturer }} {{ $asset->model }}</div>
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
                            <div class="flex justify-end items-center gap-2">
                                <button onclick="openFormModal({{ $asset->toJson() }})" class="px-3 py-2 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white rounded-lg font-bold transition-all shadow-sm text-[10px] uppercase tracking-widest flex items-center gap-1.5" title="Edit Asset">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Edit
                                </button>

                                <button onclick="openAssetModal({{ $asset->toJson() }})" class="px-3 py-2 bg-slate-800 text-white border border-slate-700 hover:bg-slate-700 rounded-lg font-bold transition-all shadow-sm text-[10px] uppercase tracking-widest flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    X-Ray
                                </button>

                                <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Yakin ingin menghapus aset ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white rounded-lg font-bold transition-all shadow-sm text-[10px] uppercase tracking-widest flex items-center gap-1.5" title="Hapus Asset">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="text-slate-400 mb-2"><i class="fa-solid {{ $currentIcon }} text-4xl"></i></div>
                            <div class="text-slate-500 font-bold text-sm">Belum ada perangkat dalam kategori {{ $selectedCategory }}.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="formModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 w-full h-screen z-[110] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="relative w-full max-w-4xl max-h-[95vh] mx-auto flex flex-col bg-white rounded-2xl shadow-2xl border-2 border-slate-300 animate-fade-in-up">

        <div class="flex items-center justify-between p-4 md:p-5 border-b-2 border-slate-200 bg-slate-50 rounded-t-2xl shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg border border-blue-200"><i class="fa-solid fa-pen-to-square text-lg"></i></div>
                <div>
                    <h3 class="text-lg font-black text-slate-900" id="modal-form-title">Edit Data {{ $selectedCategory }}</h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Manajemen Data Administratif Aset</p>
                </div>
            </div>
            <button type="button" onclick="closeFormModal()" class="text-slate-400 bg-white hover:bg-red-50 hover:text-red-600 hover:ring-2 hover:ring-red-200 transition-all rounded-lg text-sm w-8 h-8 flex justify-center items-center border border-slate-200 shadow-sm"><i class="fa-solid fa-xmark"></i></button>
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
                            <input type="text" name="asset_name" id="input_asset_name" required class="w-full text-sm rounded-lg border-blue-300 focus:border-blue-600 focus:ring-blue-500 font-bold text-blue-900">
                            <p class="text-[9px] text-blue-600 font-bold mt-1.5 leading-tight"><i class="fa-solid fa-circle-info"></i> <b>Audit Rule:</b> Jika ganti PIC, tambahkan nama baru (Contoh: <i>ASMNB0001 - Bimo/Dika</i>).</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Status Aset <span class="text-red-500">*</span></label>
                            <select name="status" id="input_status" required class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 font-bold">
                                <option value="Active">Active / In Use</option>
                                <option value="Stock">In Stock (Gudang)</option>
                                <option value="Maintenance">Under Maintenance</option>
                                <option value="Broken">Broken / Rusak</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Pabrikan</label>
                                <input type="text" name="manufacturer" id="input_manufacturer" class="w-full text-sm rounded-lg border-slate-300 font-bold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Model / Tipe</label>
                                <input type="text" name="model" id="input_model" class="w-full text-sm rounded-lg border-slate-300 font-bold">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Serial Number (SN)</label>
                            <input type="text" name="serial_number" id="input_serial_number" class="w-full text-sm rounded-lg border-slate-300 font-mono text-slate-600">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4 border-t-4 border-t-emerald-500">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-100 pb-2">2. Alokasi Lokasi (Dinamis)</h4>

                            <div>
                                <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Tipe Penempatan <span class="text-red-500">*</span></label>
                                <select id="placement_type" onchange="togglePlacement()" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold bg-emerald-50 text-emerald-800">
                                    <option value="office">Kantor</option>
                                    <option value="vessel">Kapal</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 gap-3">
                                <div id="company_select_container">
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Perusahaan / Entitas (Kantor) <span class="text-red-500">*</span></label>
                                    <select name="company_entity" id="input_company_entity" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold">
                                        <option value="PT Caraka Tirta Pratama (CTP)">PT Caraka Tirta Pratama (CTP)</option>
                                        <option value="PT Amarin Ship Management (ASM)">PT Amarin Ship Management (ASM)</option>
                                        <option value="PT Amarin Crewing Services (ACS)">PT Amarin Crewing Services (ACS)</option>
                                    </select>
                                </div>

                                <div id="vessel_select_container" class="hidden">
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Pilih Kapal <span class="text-red-500">*</span></label>
                                    <select name="vessel_id" id="input_vessel_id" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold">
                                        <option value="">-- Pilih Armada --</option>
                                        @foreach($vessels as $vessel)
                                            <option value="{{ $vessel->id }}">{{ $vessel->vessel_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">Departemen / Ruangan</label>
                                    <input type="text" name="group_name" id="input_group_name" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">PIC / Pengguna</label>
                                    <input type="text" name="contact_person" id="input_contact_person" class="w-full text-sm rounded-lg border-slate-300 focus:border-emerald-500 font-bold">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4 border-t-4 border-t-purple-500">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-100 pb-2">3. Jaringan Dasar</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">IP Address</label>
                                    <input type="text" name="ip_address" id="input_ip_address" class="w-full text-sm rounded-lg border-slate-300 font-mono text-slate-600">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black text-slate-600 uppercase tracking-wider mb-1">MAC Address</label>
                                    <input type="text" name="mac_address" id="input_mac_address" class="w-full text-sm rounded-lg border-slate-300 font-mono text-slate-600">
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

<div id="assetModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 w-full h-screen z-[100] hidden items-center justify-center bg-slate-900/70 backdrop-blur-sm transition-all duration-300 p-4">
    <div class="relative w-full max-w-5xl max-h-[90vh] mx-auto flex flex-col bg-white rounded-2xl shadow-2xl border-2 border-slate-300 animate-fade-in-up">

        <div class="flex items-center justify-between p-4 border-b-2 border-slate-200 bg-slate-800 rounded-t-2xl shrink-0 text-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-500 text-white rounded-lg"><i class="fa-solid fa-microchip text-lg"></i></div>
                <div>
                    <h3 class="text-lg font-black tracking-widest">X-RAY DIAGNOSTICS</h3>
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Device: <span id="modal-asset-name" class="text-blue-300"></span></p>
                </div>
            </div>

            <button type="button" onclick="closeAssetModal()" class="text-slate-300 hover:bg-red-500 hover:text-white transition-all rounded-lg text-sm w-8 h-8 flex justify-center items-center border border-transparent hover:border-red-400">
                <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex border-b border-slate-200 bg-slate-50 px-6 shrink-0 pt-2 gap-4">
            <button onclick="switchTab('info')" id="tab-btn-info" class="pb-3 text-xs font-black uppercase tracking-widest border-b-4 border-blue-600 text-blue-600 transition-colors"><i class="fa-solid fa-circle-info mr-1"></i> Sys Info</button>
            <button onclick="switchTab('software')" id="tab-btn-software" class="pb-3 text-xs font-black uppercase tracking-widest border-b-4 border-transparent text-slate-500 hover:text-slate-800 transition-colors"><i class="fa-brands fa-windows mr-1"></i> Software</button>
            <button onclick="switchTab('history')" id="tab-btn-history" class="pb-3 text-xs font-black uppercase tracking-widest border-b-4 border-transparent text-slate-500 hover:text-slate-800 transition-colors"><i class="fa-solid fa-clock-rotate-left mr-1"></i> History Log</button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 bg-[#f4f7fb] custom-scrollbar">

            <div id="tab-content-info" class="flex flex-col gap-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm"><div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Serial Number</div><div class="text-xs font-bold text-slate-800" id="modal-sn"></div></div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm"><div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Hardware UUID</div><div class="text-xs font-bold text-slate-800 truncate" id="modal-uuid"></div></div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm"><div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Disk Free</div><div class="text-xs font-bold text-slate-800" id="modal-disk"></div></div>
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm border-b-4 border-b-blue-500"><div class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-1">Last Boot Time</div><div class="text-xs font-bold text-slate-800" id="modal-boot"></div></div>
                </div>
            </div>

            <div id="tab-content-software" class="hidden flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden flex-1 flex flex-col min-h-[300px]">
                    <div class="p-3 bg-slate-50 border-b border-slate-200 flex justify-between items-center shrink-0">
                        <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Installed Software</h2>
                        <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-black border border-blue-200" id="software-count">0 Apps</span>
                    </div>
                    <div class="overflow-y-auto custom-scrollbar flex-1">
                        <table class="w-full text-xs text-left whitespace-nowrap"><thead class="text-[10px] text-slate-600 uppercase bg-slate-100 sticky top-0 border-b border-slate-200"><tr><th class="px-4 py-2 font-black w-10 text-center">No</th><th class="px-4 py-2 font-black">Aplikasi</th><th class="px-4 py-2 font-black">Versi</th><th class="px-4 py-2 font-black">Publisher</th></tr></thead><tbody id="software-tbody" class="divide-y divide-slate-100"></tbody></table>
                    </div>
                </div>
            </div>

            <div id="tab-content-history" class="hidden flex-col h-full">
                <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 min-h-[300px]">
                    <ol class="relative border-l-2 border-slate-200 ml-3 space-y-6" id="history-timeline"></ol>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // REVISI ANALITIS 3: FUNGSI JS DIBERSIHKAN DARI DUPLIKAT
    function togglePlacement() {
        const type = document.getElementById('placement_type').value;
        const vesselContainer = document.getElementById('vessel_select_container');
        const vesselInput = document.getElementById('input_vessel_id');
        const companyContainer = document.getElementById('company_select_container');
        const companyInput = document.getElementById('input_company_entity');

        if (type === 'vessel') {
            vesselContainer.classList.remove('hidden');
            vesselInput.required = true;

            companyContainer.classList.add('hidden');
            companyInput.required = false;
            companyInput.value = '';
        } else {
            vesselContainer.classList.add('hidden');
            vesselInput.required = false;
            vesselInput.value = '';

            companyContainer.classList.remove('hidden');
            companyInput.required = true;
        }
    }

    function openFormModal(assetData = null) {
        const modal = document.getElementById('formModal');
        const form = document.getElementById('crudForm');
        const title = document.getElementById('modal-form-title');
        const methodInput = document.getElementById('form-method');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if(assetData) {
            title.innerText = `Edit Data {{ $selectedCategory }}`;
            form.action = `/assets/${assetData.id}`;
            methodInput.value = 'PUT';

            document.getElementById('input_asset_name').value = assetData.asset_name || '';
            document.getElementById('input_status').value = assetData.status || 'Active';
            document.getElementById('input_manufacturer').value = assetData.manufacturer || '';
            document.getElementById('input_model').value = assetData.model || '';
            document.getElementById('input_serial_number').value = assetData.serial_number || '';

            if (assetData.vessel_id) {
                document.getElementById('placement_type').value = 'vessel';
                document.getElementById('input_vessel_id').value = assetData.vessel_id;
            } else {
                document.getElementById('placement_type').value = 'office';
                document.getElementById('input_company_entity').value = assetData.company_entity || 'PT Caraka Tirta Pratama (CTP)';
            }
            togglePlacement();

            document.getElementById('input_group_name').value = assetData.group_name || '';
            document.getElementById('input_contact_person').value = assetData.contact_person || '';
            document.getElementById('input_ip_address').value = assetData.ip_address || '';
            document.getElementById('input_mac_address').value = assetData.mac_address || '';
        } else {
            title.innerText = `Tambah {{ $selectedCategory }} Baru`;
            form.action = "{{ route('assets.store') }}";
            methodInput.value = 'POST';
            form.reset();

            document.getElementById('placement_type').value = 'office';
            togglePlacement();
        }
    }

    function closeFormModal() {
        document.getElementById('formModal').classList.add('hidden');
        document.getElementById('formModal').classList.remove('flex');
    }

    function switchTab(tabName) {
        ['info', 'software', 'history'].forEach(name => {
            document.getElementById(`tab-content-${name}`).classList.add('hidden');
            document.getElementById(`tab-content-${name}`).classList.remove('flex');
            document.getElementById(`tab-btn-${name}`).classList.replace('border-blue-600', 'border-transparent');
            document.getElementById(`tab-btn-${name}`).classList.replace('text-blue-600', 'text-slate-500');
        });
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        document.getElementById(`tab-content-${tabName}`).classList.add('flex');
        document.getElementById(`tab-btn-${tabName}`).classList.replace('border-transparent', 'border-blue-600');
        document.getElementById(`tab-btn-${tabName}`).classList.replace('text-slate-500', 'text-blue-600');
    }

    function openAssetModal(asset) {
        document.getElementById('assetModal').classList.remove('hidden');
        document.getElementById('assetModal').classList.add('flex');
        switchTab('info');

        document.getElementById('modal-asset-name').innerText = asset.asset_name;
        document.getElementById('modal-sn').innerText = asset.serial_number || '-';
        document.getElementById('modal-uuid').innerText = asset.hardware_uuid || '-';
        document.getElementById('modal-disk').innerText = asset.disk_space || '-';
        document.getElementById('modal-boot').innerText = asset.last_boot_time || '-';

        const tbody = document.getElementById('software-tbody');
        tbody.innerHTML = '';
        let softwareArray = [];
        if (asset.software_list) {
            softwareArray = typeof asset.software_list === 'string' ? JSON.parse(asset.software_list) : asset.software_list;
        }
        document.getElementById('software-count').innerText = `${softwareArray.length} Apps`;
        if (softwareArray.length > 0) {
            softwareArray.forEach((soft, index) => {
                tbody.innerHTML += `<tr class="hover:bg-slate-50"><td class="px-4 py-2 text-center text-slate-400 font-bold">${index + 1}</td><td class="px-4 py-2 font-bold">${soft.name || '-'}</td><td class="px-4 py-2 text-slate-600">${soft.version || '-'}</td><td class="px-4 py-2 text-[10px]">${soft.publisher || '-'}</td></tr>`;
            });
        } else {
            tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-8 text-center text-slate-400 font-bold">Tidak ada data software.</td></tr>`;
        }

        const timeline = document.getElementById('history-timeline');
        timeline.innerHTML = '';
        if (asset.logs && asset.logs.length > 0) {
            asset.logs.forEach(log => {
                let d = new Date(log.created_at);
                let timeStr = d.toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' });
                let userStr = log.user ? log.user.name : 'System/Sentinel';
                let icon = log.action === 'Created' ? '<svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>' : '<svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>';

                let details = '';
                if(log.changes) {
                    let changesObj = typeof log.changes === 'string' ? JSON.parse(log.changes) : log.changes;
                    details = '<ul class="mt-2 space-y-1 text-[10px] text-slate-500 font-mono">';
                    for (const [field, vals] of Object.entries(changesObj)) {
                        if(field === 'info') {
                            details += `<li><i class="fa-solid fa-caret-right mr-1"></i> ${vals}</li>`;
                        } else {
                            details += `<li><b class="uppercase text-slate-700">${field}:</b> <span class="line-through text-red-400">${vals.old || 'null'}</span> <i class="fa-solid fa-arrow-right mx-1 text-slate-300"></i> <span class="text-emerald-600">${vals.new || 'null'}</span></li>`;
                        }
                    }
                    details += '</ul>';
                }

                timeline.innerHTML += `
                    <li class="mb-4 ml-6">
                        <span class="absolute flex items-center justify-center w-6 h-6 bg-white rounded-full -left-3 ring-4 ring-white border border-slate-200 shadow-sm">${icon}</span>
                        <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl shadow-sm">
                            <h3 class="flex items-center mb-1 text-xs font-black text-slate-800">${log.action} <span class="bg-blue-100 text-blue-800 text-[9px] font-bold px-2 py-0.5 rounded ml-2">By: ${userStr}</span></h3>
                            <time class="block mb-2 text-[10px] font-bold leading-none text-slate-400">${timeStr}</time>
                            ${details}
                        </div>
                    </li>
                `;
            });
        } else {
            timeline.innerHTML = `<li class="ml-6 py-4 text-xs font-bold text-slate-400">Belum ada riwayat perubahan terekam.</li>`;
        }
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.add('hidden');
        document.getElementById('assetModal').classList.remove('flex');
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
