@extends('portal.layouts.app')
@section('page_title', 'My Profile')

@section('content')
<!-- Kita gunakan Alpine.js state untuk mengatur Tab Mana yang terbuka -->
<!-- Jika ada error validasi password, otomatis buka tab 'password' -->
<div class="max-w-5xl mx-auto space-y-6" x-data="{ tab: '{{ session('errors') ? 'password' : 'overview' }}' }">
    
    <!-- HEADER PROFIL & TAB BUTTONS -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <!-- Box Avatar -->
            <div class="w-20 h-20 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-3xl flex-shrink-0 border border-blue-100 shadow-inner overflow-hidden">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800">{{ Auth::user()->full_name }}</h2>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">{{ Auth::user()->jabatan ?? Auth::user()->role }}</p>
                <p class="text-xs font-medium text-slate-500 mt-1">All employee identity, photo, and security settings are grouped in one compact tab view.</p>
            </div>
        </div>
        
        <!-- Tab Navigasi -->
        <div class="flex bg-slate-50 p-1.5 rounded-2xl border border-slate-100 shadow-inner w-full md:w-auto">
            <button @click="tab = 'overview'" :class="tab === 'overview' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'" class="flex-1 md:flex-none px-6 py-2.5 text-sm font-bold rounded-xl transition-all">Overview</button>
            <button @click="tab = 'photo'" :class="tab === 'photo' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'" class="flex-1 md:flex-none px-6 py-2.5 text-sm font-bold rounded-xl transition-all">Photo</button>
            <button @click="tab = 'password'" :class="tab === 'password' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'" class="flex-1 md:flex-none px-6 py-2.5 text-sm font-bold rounded-xl transition-all">Password</button>
        </div>
    </div>

    <!-- FLASH MESSAGE SUKSES -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-3 shadow-sm">
            <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <!-- TAB 1: OVERVIEW (Data HRIS) -->
    <div x-show="tab === 'overview'" x-transition.opacity class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-y-8 gap-x-6">
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Employee Number</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->employee_code ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Join Date</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->join_date ? \Carbon\Carbon::parse(Auth::user()->join_date)->format('Y-m-d') : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Official Email</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->email_work }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">WhatsApp Number</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Job Title</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->jabatan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Department</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->department->department_name ?? 'IT' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Job Level</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->role }}</p>
            </div>
            <div>
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Employment Type</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ Auth::user()->employment_type ?? 'Permanent' }}</p>
            </div>
        </div>
        <div class="mt-8 p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <p class="text-xs font-medium text-slate-500">Basic identity is maintained centrally by HR Admin. Use the tabs for photo and password updates without scrolling through separate cards.</p>
        </div>
    </div>

    <!-- TAB 2: PHOTO UPLOAD -->
    <div x-show="tab === 'photo'" x-transition.opacity x-cloak class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8">
        <form action="{{ route('portal.profile.update-photo') }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-8 items-start">
            @csrf
            <!-- Preview Box -->
            <div class="w-40 h-40 rounded-[2rem] bg-slate-50 border border-slate-200 shadow-inner flex flex-col items-center justify-center p-2 flex-shrink-0 overflow-hidden relative group">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-full h-full object-cover rounded-3xl group-hover:scale-110 transition-transform duration-500">
                @else
                    <span class="text-6xl font-black text-blue-600">{{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}</span>
                @endif
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-camera text-white text-2xl"></i>
                </div>
            </div>
            
            <div class="flex-1 space-y-4">
                <h3 class="text-lg font-black text-slate-800">Upload New Photo</h3>
                <p class="text-sm font-medium text-slate-500">Gunakan foto yang rapi dan jelas untuk menjaga konsistensi profil Anda di dalam sistem Amarin Group.</p>
                
                <!-- File Input Custom Styling -->
                <input type="file" name="photo" accept="image/*" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                @error('photo') <span class="text-xs text-red-500 font-bold block">{{ $message }}</span> @enderror
                
                <div class="pt-2">
                    <button type="submit" class="px-8 py-3 bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 font-bold text-sm rounded-xl transition shadow-sm">
                        <i class="fas fa-upload mr-2"></i> Upload Photo
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 3: PASSWORD CHANGE -->
    <div x-show="tab === 'password'" x-transition.opacity x-cloak class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8">
        <form action="{{ route('portal.profile.update-password') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Current Password <span class="text-red-500">*</span></label>
                <input type="password" name="current_password" required class="w-full max-w-md rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-slate-50 focus:bg-white">
                @error('current_password') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password" required minlength="6" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-slate-50 focus:bg-white">
                    @error('new_password') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password_confirmation" required minlength="6" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all outline-none bg-slate-50 focus:bg-white">
                </div>
            </div>
            
            <div class="pt-4 border-t border-slate-50">
                <p class="text-xs font-medium text-slate-500 mb-5">Password change is kept here so the main overview stays compact.</p>
                <button type="submit" class="px-8 py-3 bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 font-bold text-sm rounded-xl transition shadow-sm">
                    <i class="fas fa-key mr-2"></i> Update Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection