@extends('portal.layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
    <h2 class="text-2xl font-black text-slate-800 mb-6">Informasi Profil</h2>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl">
            <span class="text-slate-400 font-bold">Nama Lengkap</span>
            <span class="font-black text-slate-700">{{ Auth::user()->full_name }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl">
            <span class="text-slate-400 font-bold">Email Kerja</span>
            <span class="font-black text-slate-700">{{ Auth::user()->email_work }}</span>
        </div>
        <div class="grid grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl">
            <span class="text-slate-400 font-bold">Kode Karyawan</span>
            <span class="font-black text-slate-700">{{ Auth::user()->employee_code }}</span>
        </div>
    </div>
</div>
@endsection