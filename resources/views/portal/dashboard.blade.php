@extends('portal.layouts.app')
@section('page_title', 'IT Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-gradient-to-r from-[#031E49] to-blue-700 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-10 text-[150px]"><i class="fas fa-globe-asia"></i></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-black mb-2">Selamat Datang, {{ explode(' ', Auth::user()->full_name)[0] }}! 👋</h1>
            <p class="text-blue-100 font-medium max-w-xl">Ini adalah pusat layanan IT Anda. Pantau tiket, kelola perangkat, dan akses berbagai sistem operasional Amarin dari satu tempat.</p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-extrabold text-slate-500 uppercase tracking-widest mb-4"><i class="fas fa-bolt text-amber-500 mr-1"></i> Quick Access</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('portal.create-ticket') }}" class="flex flex-col items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-blue-200 transition-all group">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-ticket-alt"></i></div>
                <span class="text-sm font-bold text-slate-700">Lapor Masalah</span>
            </a>
            <a href="{{ route('portal.create-ticket') }}" class="flex flex-col items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-emerald-200 transition-all group">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-desktop"></i></div>
                <span class="text-sm font-bold text-slate-700">Request Aset Baru</span>
            </a>
            <a href="{{ route('portal.my-access') }}" class="flex flex-col items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-purple-200 transition-all group">
                <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-key"></i></div>
                <span class="text-sm font-bold text-slate-700">Request Akses / VPN</span>
            </a>
            <a href="{{ route('portal.kb') }}" class="flex flex-col items-center p-5 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-amber-200 transition-all group">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-3 group-hover:scale-110 transition-transform"><i class="fas fa-book"></i></div>
                <span class="text-sm font-bold text-slate-700">Panduan (SOP)</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl text-red-500 group-hover:scale-110 transition-transform"><i class="fas fa-fire"></i></div>
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Tiket Aktif</p>
            <h3 class="text-4xl font-black text-slate-800 mb-2">{{ $activeTickets ?? 0 }}</h3>
            <p class="text-xs font-bold text-red-500"><i class="fas fa-clock mr-1"></i> Sedang ditangani IT</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl text-emerald-500 group-hover:scale-110 transition-transform"><i class="fas fa-check-circle"></i></div>
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Tiket Selesai</p>
            <h3 class="text-4xl font-black text-slate-800 mb-2">{{ $resolvedTickets ?? 0 }}</h3>
            <p class="text-xs font-bold text-emerald-500"><i class="fas fa-chart-line mr-1"></i> Performa penyelesaian</p>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 opacity-5 text-7xl text-blue-500 group-hover:scale-110 transition-transform"><i class="fas fa-laptop-code"></i></div>
            <p class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-1">Aset & Perangkat</p>
            <h3 class="text-4xl font-black text-slate-800 mb-2">{{ $myAssets ?? 0 }}</h3>
            <a href="{{ route('portal.assets') }}" class="text-xs font-bold text-blue-500 hover:underline"><i class="fas fa-eye mr-1"></i> Lihat detail aset Anda &rarr;</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-slate-800 text-lg">Riwayat Tiket Terbaru</h3>
                <a href="{{ route('portal.support') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 pr-4"><span class="font-black text-slate-700">{{ $ticket->ticket_number }}</span></td>
                            <td class="py-3 px-4"><span class="font-semibold text-slate-600 truncate max-w-[200px] block">{{ $ticket->name }}</span></td>
                            <td class="py-3 px-4 text-center">
                                @if(($ticket->status ?? 1) == 1)
                                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-blue-100 text-blue-700">New</span>
                                @elseif(($ticket->status ?? 1) == 5 || ($ticket->status ?? 1) == 6)
                                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-emerald-100 text-emerald-700">Resolved</span>
                                @else
                                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase bg-amber-100 text-amber-700">Progress</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="py-4 text-center text-slate-400 text-sm font-semibold">Belum ada riwayat tiket IT.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-black text-slate-800 text-lg mb-6"><i class="fas fa-server text-blue-500 mr-2"></i> IT System Status</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-wifi text-slate-400"></i>
                        <span class="text-sm font-bold text-slate-700">Jaringan Internet (HO)</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-database text-slate-400"></i>
                        <span class="text-sm font-bold text-slate-700">Server HRIS / ERP</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-slate-400"></i>
                        <span class="text-sm font-bold text-slate-700">Email Server (O365)</span>
                    </div>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection