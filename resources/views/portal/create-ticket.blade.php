@extends('portal.layouts.app')
@section('page_title', 'Buat Tiket Bantuan IT')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header Form -->
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Form Permintaan Layanan IT</h2>
                <p class="text-sm text-slate-500">Tiket Anda akan diteruskan ke Supervisor IT (Bpk. Hendri / Ridho) untuk dijadwalkan ke Teknisi.</p>
            </div>
        </div>

        <!-- Body Form -->
        <form action="{{ route('portal.store-ticket') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <!-- Baris 1: Subjek Masalah -->
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Subjek / Judul Masalah <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Email Outlook tidak bisa kirim pesan" 
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Kategori Laporan <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none appearance-none bg-white">
                    <option value="" disabled selected>-- Pilih Jenis Bantuan --</option>
                    <option value="Incident">⚠️ Incident (Sistem Error / Perangkat Rusak / Kecelakaan)</option>
                    <option value="Service Request">📦 Service Request (Permintaan Alat Baru / Akses Software)</option>
                    <option value="Maintenance">🔧 Maintenance (Pemeliharaan Berkala)</option>
                </select>
            </div>
            <!-- Baris 2: Prioritas -->
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Tingkat Prioritas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Low -->
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-blue-500 has-[:checked]:ring-1 has-[:checked]:ring-blue-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="1" class="sr-only" checked>
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-slate-800"><i class="fas fa-circle text-slate-400 text-xs mr-1"></i> Low (Rendah)</span>
                            <span class="mt-1 flex items-center text-xs text-slate-500">Bukan hal mendesak, misal request *mouse*.</span>
                        </span>
                    </label>
                    <!-- Medium -->
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-amber-500 has-[:checked]:ring-1 has-[:checked]:ring-amber-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="2" class="sr-only">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-slate-800"><i class="fas fa-circle text-amber-500 text-xs mr-1"></i> Medium (Sedang)</span>
                            <span class="mt-1 flex items-center text-xs text-slate-500">Mengganggu sebagian kecil pekerjaan Anda.</span>
                        </span>
                    </label>
                    <!-- High -->
                    <label class="relative flex cursor-pointer rounded-xl border border-slate-200 bg-white p-4 shadow-sm focus:outline-none has-[:checked]:border-red-500 has-[:checked]:ring-1 has-[:checked]:ring-red-500 transition-all hover:bg-slate-50">
                        <input type="radio" name="priority" value="3" class="sr-only">
                        <span class="flex flex-col">
                            <span class="block text-sm font-bold text-slate-800"><i class="fas fa-circle text-red-500 text-xs mr-1"></i> High (Mendesak)</span>
                            <span class="mt-1 flex items-center text-xs text-slate-500">Sistem mati total, operasi kapal terhenti.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Aset Terkait (Opsional)</label>
                <select name="asset_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none">
                    <option value="">-- Tidak Terkait Aset Tertentu --</option>
                    @foreach($myAssets as $asset)
                        <option value="{{ $asset->id }}" {{ request('asset') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->asset_tag }} - {{ $asset->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Baris 3: Deskripsi Lengkap -->
            <div>
                <label class="block text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2">Detail Kejadian / Permintaan <span class="text-red-500">*</span></label>
                <textarea name="description" rows="5" required placeholder="Jelaskan secara rinci masalah yang terjadi atau kebutuhan Anda..." 
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none resize-none"></textarea>
                <p class="text-[10px] text-slate-400 mt-1">*Mohon sebutkan pesan error yang muncul (jika ada).</p>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('portal.dashboard') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-md shadow-blue-500/30 transition transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Tiket
                </button>
            </div>
        </form>
    </div>
</div>
@endsection