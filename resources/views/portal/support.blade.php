@extends('portal.layouts.app')
@section('page_title', 'IT Support (Tickets)')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <!-- HEADER & ACTION BUTTON -->
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Tiket IT Anda</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau status permintaan bantuan IT, *request* aset, atau laporkan masalah baru.</p>
        </div>
        <a href="{{ route('portal.create-ticket') }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 hover:shadow-lg">
            <i class="fas fa-plus"></i> Buat Tiket Baru
        </a>
    </div>

    <!-- TABEL TIKET -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6 font-extrabold text-slate-500 uppercase text-[11px] tracking-wider">No. Tiket</th>
                        <th class="py-4 px-6 font-extrabold text-slate-500 uppercase text-[11px] tracking-wider">Subjek / Masalah</th>
                        <th class="py-4 px-6 font-extrabold text-slate-500 uppercase text-[11px] tracking-wider text-center">Prioritas</th>
                        <th class="py-4 px-6 font-extrabold text-slate-500 uppercase text-[11px] tracking-wider text-center">Status</th>
                        <th class="py-4 px-6 font-extrabold text-slate-500 uppercase text-[11px] tracking-wider">Terakhir Update</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer">
                        <td class="py-4 px-6 font-black text-blue-600 group-hover:text-blue-800">
                            {{ $ticket->ticket_number ?? 'INC-XXXX' }}
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-bold text-slate-800">{{ $ticket->name ?? 'Subjek Kosong' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 truncate max-w-xs">{{ $ticket->description ?? 'Tidak ada deskripsi' }}</p>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if(($ticket->priority ?? 1) == 3)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-600">High</span>
                            @elseif(($ticket->priority ?? 1) == 2)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-600">Medium</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-600">Low</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if(($ticket->status ?? 1) == 1)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-100 text-blue-600 border border-blue-200">New</span>
                            @elseif(($ticket->status ?? 1) == 2)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-purple-100 text-purple-600 border border-purple-200">In Progress</span>
                            @elseif(($ticket->status ?? 1) == 5 || ($ticket->status ?? 1) == 6)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-600 border border-emerald-200">Resolved</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100 text-slate-600">Pending</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-slate-500 font-semibold text-xs">
                            {{ \Carbon\Carbon::parse($ticket->updated_at)->format('d M Y - H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                                <i class="fas fa-ticket-alt text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Tiket</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Anda belum pernah membuat permintaan bantuan atau laporan kendala IT.</p>
                            <a href="{{ route('portal.create-ticket') }}" class="inline-block mt-4 text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                Buat tiket pertama Anda &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection