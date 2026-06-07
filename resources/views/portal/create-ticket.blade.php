@extends('portal.layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('portal.dashboard') }}" class="text-slate-400 hover:text-blue-600 font-black tracking-wide"><i class="fas fa-arrow-left mr-2"></i> KEMBALI</a>
    </div>
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 sm:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

        <h2 class="text-3xl font-black text-slate-800 mb-8 relative z-10">Lapor Masalah IT 🛠️</h2>

        <form action="{{ route('portal.store-ticket') }}" method="POST" class="space-y-6 relative z-10">
            @csrf
            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Apa yang bermasalah?</label>
                <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition" placeholder="Contoh: Printer Ruang HRD Error Offline">
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tingkat Kegawatan</label>
                <select name="priority" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition appearance-none">
                    <option value="1">🟢 Rendah (Masih bisa kerja, tidak buru-buru)</option>
                    <option value="2" selected>🟡 Sedang (Mengganggu alur kerja)</option>
                    <option value="3">🔴 Tinggi (Sistem lumpuh total / Darurat)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ceritakan Detailnya</label>
                <textarea name="description" required rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 font-semibold text-slate-700 outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition" placeholder="Ceritakan detail masalah yang dialami agar tim IT cepat paham..."></textarea>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-black text-lg py-4 rounded-2xl shadow-xl shadow-blue-500/30 transition-transform transform hover:-translate-y-1">
                    KIRIM LAPORAN <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
