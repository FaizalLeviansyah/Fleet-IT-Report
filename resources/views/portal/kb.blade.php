@extends('portal.layouts.app')
@section('page_title', 'Pusat Bantuan & SOP IT')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <div class="bg-[#031E49] rounded-3xl p-10 text-center relative overflow-hidden shadow-lg">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        <div class="relative z-10 max-w-2xl mx-auto">
            <h1 class="text-3xl font-black text-white mb-3">Hi, ada yang bisa kami bantu?</h1>
            <p class="text-blue-200 font-medium text-sm mb-8">Temukan panduan, SOP, dan solusi cepat untuk masalah IT sehari-hari.</p>
            
            <div class="relative">
                <i class="fas fa-search absolute left-5 top-4 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Cari panduan (contoh: cara reset password, printer error)..." 
                       class="w-full pl-14 pr-6 py-4 bg-white rounded-2xl shadow-xl text-slate-700 font-medium focus:ring-4 focus:ring-blue-500/30 outline-none transition-all">
                <button class="absolute right-2 top-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold transition-colors">Cari</button>
            </div>
        </div>
    </div>

    <div>
        <h3 class="font-black text-slate-800 mb-4 flex items-center gap-2"><i class="fas fa-folder-open text-blue-500"></i> Kategori Populer</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-blue-200 cursor-pointer transition-all text-center group">
                <div class="w-12 h-12 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-envelope"></i></div>
                <span class="text-sm font-bold text-slate-700">Email & O365</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:emerald-200 cursor-pointer transition-all text-center group">
                <div class="w-12 h-12 mx-auto bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-print"></i></div>
                <span class="text-sm font-bold text-slate-700">Printer & Scanner</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-purple-200 cursor-pointer transition-all text-center group">
                <div class="w-12 h-12 mx-auto bg-purple-50 text-purple-600 rounded-full flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-network-wired"></i></div>
                <span class="text-sm font-bold text-slate-700">Jaringan & VPN</span>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-amber-200 cursor-pointer transition-all text-center group">
                <div class="w-12 h-12 mx-auto bg-amber-50 text-amber-600 rounded-full flex items-center justify-center text-xl mb-2 group-hover:scale-110 transition-transform"><i class="fas fa-ship"></i></div>
                <span class="text-sm font-bold text-slate-700">Vessel IT System</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 md:p-8">
        <h3 class="font-black text-slate-800 text-lg mb-6 border-b border-slate-100 pb-4">Artikel Panduan Terbaru</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($articles as $article)
            <a href="#" class="flex gap-4 p-4 rounded-2xl hover:bg-slate-50 border border-transparent hover:border-slate-100 transition-all group">
                <div class="w-14 h-14 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl flex-shrink-0 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-base group-hover:text-blue-600 transition-colors">{{ $article->title }}</h4>
                    <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-2">{{ strip_tags($article->content) }}</p>
                    <div class="flex items-center gap-3 mt-2 text-[10px] font-bold text-slate-400">
                        <span><i class="fas fa-clock mr-1"></i> {{ $article->created_at->diffForHumans() }}</span>
                        <span><i class="fas fa-eye mr-1"></i> {{ rand(10, 150) }} Views</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-10 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-2xl text-slate-400 mx-auto mb-3"><i class="fas fa-folder-open"></i></div>
                <p class="font-bold text-slate-600">Belum ada panduan / SOP yang diunggah.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection