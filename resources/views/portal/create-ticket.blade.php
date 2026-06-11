@extends('portal.layouts.app')
@section('page_title', 'Buat Tiket IT')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <!-- Header Form -->
        <div class="px-8 py-8 border-b border-slate-50 bg-slate-50/50 flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-500/30">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800">Form Permintaan Layanan IT</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Lengkapi form di bawah ini agar tim IT dapat segera membantu Anda.</p>
            </div>
        </div>

        <!-- Body Form -->
        <form action="{{ route('portal.store-ticket') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- Baris 1: Judul & Kategori -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Subjek / Judul Masalah <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Email Outlook error..." 
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-slate-50 focus:bg-white">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Kategori Laporan <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none appearance-none bg-slate-50 focus:bg-white cursor-pointer">
                        <option value="" disabled selected>-- Pilih Jenis Bantuan --</option>
                        <option value="Incident">⚠️ Incident (Sistem Error / Hardware Rusak)</option>
                        <option value="Service Request">📦 Service Request (Minta Alat Baru / Akses)</option>
                    </select>
                </div>
            </div>

            <!-- Baris 2: Pemilihan Aset (Grouped Dropdown) -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Pilih Aset Terkait (Opsional)</label>
                <select name="asset_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none appearance-none bg-slate-50 focus:bg-white cursor-pointer">
                    <option value="">-- Tidak Terkait Aset / Software Issue --</option>
                    
                    <optgroup label="💻 Aset Milik Saya" class="font-bold text-blue-600">
                        @foreach($myAssets as $asset)
                            <option value="{{ $asset->id }}" class="text-slate-700 font-medium" {{ request('asset') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag ?? 'NO-TAG' }} - {{ $asset->asset_name }}
                            </option>
                        @endforeach
                    </optgroup>
                    
                    <optgroup label="🖨️ Aset Umum / Kantor" class="font-bold text-emerald-600">
                        @foreach($generalAssets as $asset)
                            <option value="{{ $asset->id }}" class="text-slate-700 font-medium" {{ request('asset') == $asset->id ? 'selected' : '' }}>
                                {{ $asset->asset_tag ?? 'NO-TAG' }} - {{ $asset->asset_name }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
                <p class="text-[10px] font-bold text-slate-400 mt-1.5"><i class="fas fa-info-circle"></i> Pilih aset umum jika melaporkan masalah pada Printer Ruangan, CCTV, atau Proyektor.</p>
            </div>

            <!-- Baris 3: Prioritas (Radio Cards) -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Tingkat Prioritas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative flex cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-slate-500 has-[:checked]:ring-1 has-[:checked]:ring-slate-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="1" class="sr-only" checked>
                        <span class="flex flex-col">
                            <span class="block text-sm font-black text-slate-700 mb-1">Low (Rendah)</span>
                            <span class="text-xs font-medium text-slate-500">Bukan hal mendesak, misal request *mouse*.</span>
                        </span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-amber-500 has-[:checked]:ring-1 has-[:checked]:ring-amber-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="2" class="sr-only">
                        <span class="flex flex-col">
                            <span class="block text-sm font-black text-amber-600 mb-1">Medium (Sedang)</span>
                            <span class="text-xs font-medium text-slate-500">Mengganggu sebagian kecil pekerjaan Anda.</span>
                        </span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-red-500 has-[:checked]:ring-1 has-[:checked]:ring-red-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="3" class="sr-only">
                        <span class="flex flex-col">
                            <span class="block text-sm font-black text-red-600 mb-1">High (Mendesak)</span>
                            <span class="text-xs font-medium text-slate-500">Sistem mati total, operasi terhenti.</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Baris 4: Deskripsi -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Detail Kejadian <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required placeholder="Jelaskan secara rinci masalah yang terjadi..." 
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none resize-none bg-slate-50 focus:bg-white"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('portal.dashboard') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-8 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Tiket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection