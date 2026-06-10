@extends('portal.layouts.app')
@section('page_title', 'Hak Akses & VPN')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-800">Daftar Hak Akses Sistem Anda</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Berikut adalah daftar aplikasi internal dan layanan jaringan yang terikat pada akun Anda.</p>
        </div>
        <a href="{{ route('portal.create-ticket') }}" class="px-5 py-2.5 bg-blue-50 text-blue-600 font-bold text-sm rounded-xl hover:bg-blue-100 transition border border-blue-100 shadow-sm">
            <i class="fas fa-plus mr-1"></i> Request Akses Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <div class="bg-white p-6 rounded-3xl border border-emerald-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-2xl text-[#031E49] group-hover:scale-110 transition-transform"><i class="fas fa-desktop"></i></div>
                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg border border-emerald-100">Granted</span>
            </div>
            <h3 class="font-black text-slate-800 text-lg">Amarin HRIS</h3>
            <p class="text-xs font-medium text-slate-500 mt-1 mb-4">Portal HR, Absensi, dan Payroll.</p>
            <button class="w-full py-2 bg-slate-50 text-slate-400 text-xs font-bold rounded-lg cursor-not-allowed">Autentikasi via NIK</button>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-emerald-200 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
            <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-2xl text-blue-600 group-hover:scale-110 transition-transform"><i class="fab fa-microsoft"></i></div>
                <span class="px-2 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase rounded-lg border border-emerald-100">Granted</span>
            </div>
            <h3 class="font-black text-slate-800 text-lg">Microsoft 365 (Email)</h3>
            <p class="text-xs font-medium text-slate-500 mt-1 mb-4">Akses Outlook, Teams, & OneDrive.</p>
            <button class="w-full py-2 bg-slate-50 text-slate-600 hover:text-blue-600 text-xs font-bold rounded-lg border border-slate-200 hover:border-blue-200 transition">Reset Password O365</button>
        </div>

        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-inner relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4 opacity-50">
                <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-2xl text-slate-400"><i class="fas fa-network-wired"></i></div>
                <span class="px-2 py-1 bg-slate-200 text-slate-500 text-[10px] font-black uppercase rounded-lg">No Access</span>
            </div>
            <h3 class="font-black text-slate-500 text-lg">Fortinet Global VPN</h3>
            <p class="text-xs font-medium text-slate-400 mt-1 mb-4">Akses jaringan kantor dari luar area.</p>
            <a href="{{ route('portal.create-ticket') }}" class="block text-center w-full py-2 bg-white text-blue-600 hover:bg-blue-50 text-xs font-bold rounded-lg border border-blue-200 transition shadow-sm">Request Akses VPN</a>
        </div>

    </div>
</div>
@endsection