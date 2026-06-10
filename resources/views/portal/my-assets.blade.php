@extends('portal.layouts.app')
@section('page_title', 'inventaris aset it saya')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">perangkat kerja anda</h2>
            <p class="text-sm text-slate-500 mt-1">daftar perangkat keras dan lisensi perangkat lunak yang diamanahkan kepada anda.</p>
        </div>
        <div class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-600 font-bold py-2.5 px-6 rounded-xl border border-slate-200">
            <i class="fas fa-boxes"></i> total: {{ $assets->count() }} perangkat
        </div>
    </div>

    <!-- grid visualisasi aset -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assets as $asset)
        <!-- kartu aset -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            
            <!-- bagian atas card (gradient & icon) -->
            <div class="h-24 bg-gradient-to-br from-slate-800 to-slate-900 relative p-5 flex items-start justify-between overflow-hidden">
                <!-- pola latar -->
                <div class="absolute -right-4 -top-4 opacity-10 text-8xl group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-laptop"></i> <!-- bisa di-dinamiskan berdasarkan kategori aset -->
                </div>
                
                <div class="relative z-10">
                    <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-black uppercase rounded-lg tracking-wider">
                        {{ $asset->asset_tag ?? 'no-tag' }}
                    </span>
                </div>
                <div class="relative z-10">
                    @if(($asset->status ?? 'active') === 'active')
                        <span class="w-3 h-3 rounded-full bg-emerald-400 border-2 border-white shadow-sm block animate-pulse" title="active"></span>
                    @else
                        <span class="w-3 h-3 rounded-full bg-red-400 border-2 border-white shadow-sm block" title="in repair"></span>
                    @endif
                </div>
            </div>

            <!-- bagian bawah card (detail & specs) -->
            <div class="p-5 relative -mt-6">
                <!-- ikon timbul -->
                <div class="w-12 h-12 bg-white rounded-xl shadow-md border border-slate-100 flex items-center justify-center text-xl text-blue-600 mb-3">
                    <i class="fas fa-desktop"></i>
                </div>

                <h3 class="font-black text-lg text-slate-800 leading-tight mb-1">{{ $asset->name }}</h3>
                <p class="text-xs font-semibold text-blue-600 mb-4">{{ $asset->category->name ?? 'perangkat it' }}</p>

                <!-- spesifikasi mini -->
                <div class="space-y-2 mb-5">
                    <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium"><i class="fas fa-barcode w-4 text-center mr-1"></i> serial no.</span>
                        <span class="font-bold text-slate-800">{{ $asset->serial_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium"><i class="fas fa-microchip w-4 text-center mr-1"></i> spesifikasi</span>
                        <span class="font-bold text-slate-800 text-right max-w-[150px] truncate" title="{{ $asset->notes }}">{{ $asset->notes ?? 'standar' }}</span>
                    </div>
                </div>

                <!-- tombol lapor rusak -->
                <a href="{{ route('portal.create-ticket') }}?asset={{ $asset->id }}" class="w-full flex items-center justify-center gap-2 bg-slate-50 hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2 rounded-xl transition-colors text-sm">
                    <i class="fas fa-tools"></i> lapor masalah aset ini
                </a>
            </div>
        </div>
        @empty
        <!-- state jika tidak punya aset -->
        <div class="col-span-full bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-3xl text-slate-400 mx-auto mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">belum ada aset terdaftar</h3>
            <p class="text-sm text-slate-500">saat ini tidak ada perangkat it atau aset perusahaan yang terdaftar atas nama anda.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection