@extends('portal.layouts.app')

@section('content')
<div class="mb-10 text-center">
    <h1 class="text-4xl font-black text-slate-800">Pusat Bantuan (SOP) 📚</h1>
    <p class="text-slate-500 mt-3 font-semibold">Cari solusi mandiri sebelum membuat laporan ke IT.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($articles as $article)
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 text-xl">
            <i class="fas fa-book-open"></i>
        </div>
        <h3 class="font-black text-xl text-slate-800 mb-3">{{ $article->title }}</h3>
        <p class="text-slate-500 text-sm mb-6 line-clamp-3 font-medium leading-relaxed">{{ strip_tags($article->content) }}</p>
        <button class="text-sm font-black text-blue-600 hover:text-blue-700">Baca Panduan &rarr;</button>
    </div>
    @empty
    <div class="col-span-full text-center py-16 bg-white rounded-[2rem] border border-slate-100 shadow-sm">
        <div class="text-6xl mb-4">📭</div>
        <p class="text-slate-400 font-black text-lg">Belum ada panduan yang diterbitkan oleh Tim IT.</p>
    </div>
    @endforelse
</div>
@endsection
