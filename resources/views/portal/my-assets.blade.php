@extends('portal.layouts.app')
@section('page_title', 'Inventaris Aset IT Saya')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Perangkat Kerja Anda</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar perangkat keras dan lisensi perangkat lunak yang diamanahkan kepada Anda.</p>
        </div>
        <div class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-600 font-bold py-2.5 px-6 rounded-xl border border-slate-200">
            <i class="fas fa-boxes"></i> Total: {{ $assets->count() }} Perangkat
        </div>
    </div>

    <!-- GRID VISUALISASI ASET -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($assets as $asset)
        <!-- Kartu Aset -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
            
            <!-- Bagian Atas Card (Gradient & Icon) -->
            <div class="h-24 bg-gradient-to-br from-slate-800 to-slate-900 relative p-5 flex items-start justify-between overflow-hidden">
                <!-- Pola Latar -->
                <div class="absolute -right-4 -top-4 opacity-10 text-8xl group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-laptop"></i> <!-- Bisa di-dinamiskan berdasarkan kategori aset -->
                </div>
                
                <div class="relative z-10">
                    <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm border border-white/30 text-white text-[10px] font-black uppercase rounded-lg tracking-wider">
                        {{ $asset->asset_tag ?? 'NO-TAG' }}
                    </span>
                </div>
                <div class="relative z-10">
                    @if(($asset->status ?? 'Active') === 'Active')
                        <span class="w-3 h-3 rounded-full bg-emerald-400 border-2 border-white shadow-sm block animate-pulse" title="Active"></span>
                    @else
                        <span class="w-3 h-3 rounded-full bg-red-400 border-2 border-white shadow-sm block" title="In Repair"></span>
                    @endif
                </div>
            </div>

            <!-- Bagian Bawah Card (Detail & Specs) -->
            <div class="p-5 relative -mt-6">
                <!-- Ikon Timbul -->
                <div class="w-12 h-12 bg-white rounded-xl shadow-md border border-slate-100 flex items-center justify-center text-xl text-blue-600 mb-3">
                    <i class="fas fa-desktop"></i>
                </div>

                <h3 class="font-black text-lg text-slate-800 leading-tight mb-1">{{ $asset->name }}</h3>
                <p class="text-xs font-semibold text-blue-600 mb-4">{{ $asset->category->name ?? 'Perangkat IT' }}</p>

                <!-- Spesifikasi Mini -->
                <div class="space-y-2 mb-5">
                    <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium"><i class="fas fa-barcode w-4 text-center mr-1"></i> Serial No.</span>
                        <span class="font-bold text-slate-800">{{ $asset->serial_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium"><i class="fas fa-microchip w-4 text-center mr-1"></i> Spesifikasi</span>
                        <span class="font-bold text-slate-800 text-right max-w-[150px] truncate" title="{{ $asset->notes }}">{{ $asset->notes ?? 'Standar' }}</span>
                    </div>
                </div>

                <!-- Tombol Lapor Rusak -->
                <a href="{{ route('portal.create-ticket') }}?asset={{ $asset->id }}" class="w-full flex items-center justify-center gap-2 bg-slate-50 hover:bg-red-50 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 font-bold py-2 rounded-xl transition-colors text-sm">
                    <i class="fas fa-tools"></i> Lapor Masalah Aset Ini
                </a>
            </div>
        </div>
        @empty
        <!-- State Jika Tidak Punya Aset -->
        <div class="col-span-full bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-3xl text-slate-400 mx-auto mb-4">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Aset Terdaftar</h3>
            <p class="text-sm text-slate-500">Saat ini tidak ada perangkat IT atau aset perusahaan yang terdaftar atas nama Anda.</p>
        </div>
        @endforelse
    </div>

</div>
@endsection