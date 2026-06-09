@extends('portal.layouts.app')
@section('page_title', 'Overview Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Tiket Saya (Aktif)</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $activeTickets ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Tiket Selesai</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $resolvedTickets ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-full bg-green-100 text-green-500 flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wide">Aset IT Saya</p>
                <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $myAssets ?? 0 }}</h3>
            </div>
            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center text-2xl shadow-inner">
                <i class="fas fa-laptop"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h4 class="font-bold text-slate-800 text-lg">Riwayat Tiket Terbaru</h4>
            <a href="{{ route('portal.support') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
        </div>
        <div class="p-6">
            @if(isset($recentTickets) && $recentTickets->count() > 0)
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100">
                            <th class="pb-3 font-bold">No. Tiket</th>
                            <th class="pb-3 font-bold">Judul Masalah</th>
                            <th class="pb-3 font-bold">Status</th>
                            <th class="pb-3 font-bold">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700">
                        @foreach($recentTickets as $ticket)
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition">
                            <td class="py-4 font-bold text-blue-600">#{{ $ticket->ticket_number }}</td>
                            <td class="py-4 font-semibold">{{ $ticket->title }}</td>
                            <td class="py-4">
                                <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full bg-slate-100">{{ $ticket->status_name ?? 'PENDING' }}</span>
                            </td>
                            <td class="py-4 text-slate-500 font-medium">{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-10 text-slate-400">
                    <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                    <p class="font-semibold text-sm">Belum ada riwayat tiket IT.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection