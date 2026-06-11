@extends('portal.layouts.app')
@section('page_title', 'My Tickets')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 md:p-8 rounded-3xl border border-slate-100 shadow-sm gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-800">Daftar Tiket IT Saya</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Pantau status laporan kendala dan permintaan layanan IT Anda di sini.</p>
        </div>
        <a href="{{ route('portal.create-ticket') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-blue-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fas fa-plus"></i> Buat Tiket Baru
        </a>
    </div>

    <!-- TABEL TIKET -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-700"><i class="fas fa-list text-blue-500 mr-2"></i> Riwayat Laporan</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white border-b border-slate-100">
                    <tr>
                        <th class="py-4 px-6 font-extrabold text-slate-400 uppercase text-[10px] tracking-widest">No. Tiket</th>
                        <th class="py-4 px-6 font-extrabold text-slate-400 uppercase text-[10px] tracking-widest">Detail Kendala</th>
                        <th class="py-4 px-6 font-extrabold text-slate-400 uppercase text-[10px] tracking-widest text-center">Prioritas</th>
                        <th class="py-4 px-6 font-extrabold text-slate-400 uppercase text-[10px] tracking-widest text-center">Status</th>
                        <th class="py-4 px-6 font-extrabold text-slate-400 uppercase text-[10px] tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="py-4 px-6 font-black text-blue-600">
                            {{ $ticket->ticket_number }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">{{ $ticket->name }}</p>
                            <p class="text-xs font-medium text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d M Y - H:i') }}</p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if(($ticket->priority ?? 1) == 3)
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-red-50 text-red-600 border border-red-100">High</span>
                            @elseif(($ticket->priority ?? 1) == 2)
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100">Medium</span>
                            @else
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-slate-50 text-slate-600 border border-slate-200">Low</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if(($ticket->status ?? 1) == 1)
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-blue-50 text-blue-600 border border-blue-100"><i class="fas fa-asterisk mr-1"></i> New</span>
                            @elseif(($ticket->status ?? 1) == 5 || ($ticket->status ?? 1) == 6)
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-emerald-50 text-emerald-600 border border-emerald-100"><i class="fas fa-check mr-1"></i> Resolved</span>
                            @else
                                <span class="px-3 py-1 rounded-md text-[10px] font-black uppercase bg-purple-50 text-purple-600 border border-purple-100"><i class="fas fa-spinner fa-spin mr-1"></i> Progress</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <a href="{{ route('portal.show-ticket', $ticket->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 transition-all">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                                <i class="fas fa-ticket-alt text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Tiket</h3>
                            <p class="text-slate-500 font-medium text-sm">Anda belum pernah membuat laporan kendala IT.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection