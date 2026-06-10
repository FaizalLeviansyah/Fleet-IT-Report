@extends('portal.layouts.app')
@section('page_title', 'My Profile')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="h-32 bg-gradient-to-r from-blue-600 to-[#031E49] relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        </div>
        
        <div class="px-8 pb-8 relative">
            <div class="flex flex-col md:flex-row gap-6 md:items-end -mt-12 mb-4">
                <div class="w-24 h-24 rounded-2xl bg-white p-1.5 shadow-md border border-slate-100 relative z-10 flex-shrink-0">
                    <div class="w-full h-full bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-black text-3xl">
                        {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                    </div>
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-slate-800">{{ Auth::user()->full_name }}</h2>
                    <p class="text-sm font-bold text-blue-600">{{ Auth::user()->jabatan ?? Auth::user()->role }} <span class="text-slate-300 mx-2">|</span> PT Amarin Ship Management</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-5 py-2 bg-slate-100 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-200 transition"><i class="fas fa-lock mr-1"></i> Ubah Password</button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-black text-slate-800 text-base mb-4 border-b border-slate-100 pb-3">Informasi Pegawai (HRIS Data)</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-bold text-slate-400">NIP / Employee Code</p>
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->employee_code ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400">Email Perusahaan</p>
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->email_work }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400">Status Karyawan</p>
                    <p class="text-sm font-bold text-emerald-600 bg-emerald-50 inline-block px-2 py-1 rounded-md mt-1">{{ Auth::user()->employment_status ?? 'Active' }}</p>
                </div>
            </div>
            <p class="text-[10px] text-slate-400 mt-6 italic">*Data profil di atas disinkronisasi otomatis dari sistem HRIS Pusat.</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-black text-slate-800 text-base mb-4 border-b border-slate-100 pb-3">Pengaturan Portal IT</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-800">Notifikasi Email</p>
                        <p class="text-xs text-slate-500">Terima update saat tiket direspon</p>
                    </div>
                    <div class="w-10 h-6 rounded-full bg-blue-500 flex items-center p-1 cursor-pointer">
                        <div class="w-4 h-4 bg-white rounded-full shadow-sm transform translate-x-4"></div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-800">Notifikasi WhatsApp</p>
                        <p class="text-xs text-slate-500">Update via Amarin Bot</p>
                    </div>
                    <div class="w-10 h-6 rounded-full bg-slate-200 flex items-center p-1 cursor-pointer">
                        <div class="w-4 h-4 bg-white rounded-full shadow-sm transform translate-x-0"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection