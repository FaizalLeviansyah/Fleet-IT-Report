@extends('portal.layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-black text-slate-800">Halo, {{ Auth::user()->full_name ?? 'Kawan' }}! 👋</h1>
        <p class="text-slate-500 mt-1 font-semibold">Pantau status laporan IT Anda dengan mudah di sini.</p>
    </div>
    <a href="{{ route('portal.create-ticket') }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-black py-3 px-6 rounded-2xl shadow-lg shadow-blue-500/30 transition-transform transform hover:-translate-y-1">
        <i class="fas fa-plus mr-2"></i> Buat Laporan Baru
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r-xl mb-6 shadow-sm">
        <p class="font-black">Berhasil!</p>
        <p class="font-semibold">{{ session('success') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6">
        <div class="bg-amber-100 text-amber-500 w-16 h-16 rounded-2xl flex items-center justify-center text-3xl"><i class="fas fa-tools"></i></div>
        <div>
            <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest">Laporan Diproses</h3>
            <p class="text-4xl font-black text-slate-800">{{ $activeCount }}</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-6">
        <div class="bg-emerald-100 text-emerald-500 w-16 h-16 rounded-2xl flex items-center justify-center text-3xl"><i class="fas fa-check-double"></i></div>
        <div>
            <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest">Laporan Selesai</h3>
            <p class="text-4xl font-black text-slate-800">{{ $solvedCount }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
        <h2 class="font-black text-slate-700">Riwayat Tiket Saya</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-400 text-xs font-black uppercase tracking-wider">
                    <th class="p-5 border-b">No. Tiket</th>
                    <th class="p-5 border-b">Judul Laporan</th>
                    <th class="p-5 border-b">Status</th>
                    <th class="p-5 border-b">Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-sm font-semibold text-slate-600">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-slate-50 border-b border-slate-50 transition">
                    <td class="p-5 font-black text-blue-600">{{ $ticket->ticket_number }}</td>
                    <td class="p-5">{{ $ticket->name }}</td>
                    <td class="p-5">
                        @if(in_array($ticket->status, [1,2]))
                            <span class="bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg text-xs font-black"><i class="fas fa-clock mr-1"></i> Menunggu IT</span>
                        @elseif(in_array($ticket->status, [3,4]))
                            <span class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-black"><i class="fas fa-cog fa-spin mr-1"></i> Sedang Dikerjakan</span>
                        @else
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-black"><i class="fas fa-check mr-1"></i> Selesai</span>
                        @endif
                    </td>
                    <td class="p-5 text-slate-400">{{ $ticket->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="p-10 text-center text-slate-400 font-bold">Belum ada riwayat laporan kerusakan. Santai! ☕</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
