<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Karyawan - PT ASM</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
    <link href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css)" rel="stylesheet">
    <link href="[https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap)" rel="stylesheet">
    
    <script defer src="[https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js](https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js)"></script>
    
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>

<body x-data="{ darkMode: false, compactMode: false, mobileMenuOpen: false }" 
      :class="{ 'dark bg-slate-900': darkMode, 'bg-slate-50': !darkMode }" 
      class="text-slate-800 transition-colors duration-300 h-screen overflow-hidden flex flex-col">

    @if(session('welcome_msg'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-10"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-6 right-6 z-[100000] bg-white border border-slate-200 p-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[300px]">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 border border-emerald-100">
            <i class="fas fa-hand-sparkles"></i>
        </div>
        <div>
            <h4 class="text-sm font-black text-slate-800 mb-0.5">Login Berhasil!</h4>
            <p class="text-xs font-medium text-slate-500">{{ session('welcome_msg') }}</p>
        </div>
        <button @click="show = false" class="ml-auto text-slate-400 hover:text-red-500 transition-colors"><i class="fas fa-times"></i></button>
    </div>
    @endif

    <nav class="bg-white shadow-sm border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-xl px-4 py-1.5 rounded-xl shadow-md">ITSM</div>
            <a href="{{ route('portal.dashboard') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">Dashboard Saya</a>
            <a href="{{ route('portal.kb') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">Pusat Bantuan</a>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-bold text-sm bg-slate-100 px-4 py-2 rounded-full">{{ Auth::user()->full_name ?? Auth::user()->email_work }}</span>
            <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                @csrf
                <button type="submit" class="text-red-500 font-extrabold text-sm hover:underline bg-red-50 px-4 py-2 rounded-full"><i class="fas fa-power-off"></i> Logout</button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6 mt-4 w-full flex-1 overflow-y-auto">
        @yield('content')
    </main>
    
</body>
</html>
{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amarin IT Fleet Report</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('img/Logo_PT_ASM.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background-color: #f4f7fb !important; color: #0f172a !important; overflow-x: hidden; }
        .ambient-glow { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; pointer-events: none; background: radial-gradient(circle at 0% 0%, rgba(59, 130, 246, 0.12), transparent 40%), radial-gradient(circle at 100% 100%, rgba(6, 182, 212, 0.12), transparent 40%); }
        .glass-panel { background-color: #ffffff !important; border-bottom: 2px solid #cbd5e1 !important; box-shadow: 0 4px 10px -2px rgba(0,0,0,0.05) !important; }
        .sidebar-glass { background-color: #ffffff !important; border-right: 2px solid #cbd5e1 !important; box-shadow: 4px 0 15px -2px rgba(0,0,0,0.05) !important; }

        .nav-item-active { background: linear-gradient(135deg, #1e40af 0%, #0ea5e9 100%) !important; color: #ffffff !important; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.4) !important; border: 1px solid #1d4ed8 !important; transform: scale(1.02); transition: all 0.3s ease; }
        .nav-item-active svg { color: #ffffff !important; stroke: #ffffff !important; }
        .nav-item-hover:hover { background-color: #f1f5f9; border-color: #cbd5e1; transform: translateX(4px); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        table { border: 1px solid #cbd5e1 !important; border-collapse: collapse !important; width: 100%; }
        thead th { background-color: #e2e8f0 !important; color: #1e293b !important; font-weight: 800 !important; border-bottom: 2px solid #94a3b8 !important; border-right: 1px solid #cbd5e1 !important; padding: 12px 16px !important; }
        tbody td { border-bottom: 1px solid #cbd5e1 !important; border-right: 1px solid #f1f5f9 !important; color: #0f172a !important; font-weight: 600 !important; }
        tbody tr:hover td { background-color: #f8fafc !important; }

        .page-transition { animation: fadeInPage 0.4s ease-out forwards; }
        @keyframes fadeInPage { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
        .fancy-header { background: linear-gradient(135deg, #1e40af 0%, #0ea5e9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
    </style>
</head>
<body class="antialiased selection:bg-blue-200 selection:text-blue-900">
    <div class="ambient-glow"></div>

    <nav class="fixed top-0 z-50 w-full glass-panel transition-all duration-300">
      <div class="px-4 py-3 lg:px-6">
        <div class="flex items-center justify-between">
          <div class="flex items-center justify-start gap-3">
            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-slate-500 rounded-lg sm:hidden hover:bg-slate-100 border border-slate-300 focus:ring-4 focus:ring-blue-100 transition-all">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
              <img src="{{ asset('img/Logo_PT_ASM.jpg') }}" alt="Amarin Logo" class="h-10 w-auto object-contain rounded-md shadow-sm border border-slate-200 bg-white group-hover:scale-105 transition-transform">
              <div class="flex flex-col">
                  <span class="text-xl font-black tracking-tight text-slate-800 group-hover:text-blue-700 transition-colors">AMARIN<span class="text-blue-600">IT</span></span>
                  <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest -mt-0.5">Management System</span>
              </div>
            </a>
          </div>
          <div class="flex items-center">
              <button type="button" class="flex text-sm bg-white rounded-full border-2 border-slate-300 p-0.5 hover:ring-4 hover:ring-blue-100 transition-all" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                  <img class="w-8 h-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->full_name ?? 'IT') }}&background=1e40af&color=fff&bold=true" alt="user photo">
              </button>
              <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-slate-200 rounded-xl shadow-xl border-2 border-slate-200 w-60 overflow-hidden" id="dropdown-user">
                  <div class="px-5 py-4 bg-slate-100 border-b border-slate-200">
                    <p class="text-sm font-black text-slate-800 truncate">{{ Auth::user()->full_name ?? 'IT Staff' }}</p>
                    <p class="text-xs font-bold text-slate-600 truncate">{{ Auth::user()->email_work ?? 'it@amarin.com' }}</p>
                  </div>
                  <ul class="py-2 bg-white">
                    <li class="border-t border-slate-200 mt-1 pt-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-5 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sign out
                            </button>
                        </form>
                    </li>
                  </ul>
              </div>
          </div>
        </div>
      </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen pt-24 transition-transform -translate-x-full sidebar-glass sm:translate-x-0">
       <div class="h-full px-4 pb-4 overflow-y-auto custom-scrollbar">
          <div class="mb-3 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest mt-2">Overview</div>
          <ul class="space-y-2 font-medium mb-8">
             <li>
                <a href="{{ route('dashboard') }}" class="group flex items-center p-3 rounded-lg transition-all border {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'nav-item-hover text-slate-700' }}">
                   <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                   <span class="ml-3 font-bold">Dashboard Armada</span>
                </a>
             </li>
          </ul>

          <div class="mb-3 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Manajemen Laporan</div>
          <ul class="space-y-3 font-medium mb-8 px-2">
             <li>
                <a href="{{ route('reports.index') }}" class="group flex items-center p-3.5 rounded-xl transition-all border {{ request()->routeIs('reports.index') ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm transform scale-105' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm hover:scale-105' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('reports.index') ? 'text-blue-600' : 'text-blue-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span class="ml-3 font-bold text-[13px]">Update Laporan</span>
                </a>
             </li>
             <li>
                <a href="{{ route('personal.reports.index') }}" class="group flex items-center p-3.5 rounded-xl transition-all border {{ request()->routeIs('personal.reports.*') ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm transform scale-105' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm hover:scale-105' }}">
                   <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('personal.reports.*') ? 'text-blue-600' : 'text-slate-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                   <span class="ml-3 font-bold text-[13px]">Laporan Kinerja IT</span>
                </a>
             </li>
             <li>
                <a href="{{ route('reports.history') }}" class="group flex items-center p-3.5 rounded-xl transition-all border {{ request()->routeIs('reports.history') ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm transform scale-105' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm hover:scale-105' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('reports.history') ? 'text-blue-600' : 'text-slate-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="ml-3 font-bold text-[13px]">Riwayat Laporan</span>
                </a>
             </li>
          </ul>
          <div class="mb-3 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Administrator</div>
          <ul class="space-y-3 font-medium mb-8 px-2">
             <li>
                <a href="{{ route('master.vessels.index') }}" class="group flex items-center p-3.5 rounded-xl transition-all border {{ request()->routeIs('master.vessels.*') ? 'bg-indigo-50 border-indigo-200 text-indigo-700 shadow-sm transform scale-105' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm hover:scale-105' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('master.vessels.*') ? 'text-indigo-600' : 'text-slate-500 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="ml-3 font-bold text-[13px]">Master Data Armada</span>
                </a>
             </li>
             <li>
                <button type="button" onclick="document.getElementById('submenu-assets').classList.toggle('hidden')" class="w-full group flex items-center justify-between p-3.5 rounded-xl transition-all border {{ request()->routeIs('assets.*') ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('assets.*') ? 'text-blue-600' : 'text-slate-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        <span class="ml-3 font-bold text-[13px]">IT Asset & Inventory</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <ul id="submenu-assets" class="mt-2 space-y-1 pl-11 pr-2 {{ request()->routeIs('assets.*') ? '' : 'hidden' }}">
                    @php $assetCategories = \App\Models\AssetCategory::all(); @endphp
                    @foreach($assetCategories as $cat)
                    <li>
                        <a href="{{ route('assets.index', ['category' => $cat->name]) }}" class="flex items-center justify-between p-2.5 rounded-lg transition-all {{ request('category', 'Computers') == $cat->name && request()->routeIs('assets.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 hover:text-blue-600' }}">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid {{ $cat->icon }} w-4 text-center"></i>
                                <span class="text-[11px] font-black uppercase tracking-wider">{{ $cat->name }}</span>
                            </div>
                            <span class="text-[9px] font-black {{ request('category', 'Computers') == $cat->name ? 'text-blue-200' : 'text-slate-400' }}">{{ \App\Models\Asset::where('category_id', $cat->id)->count() }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </li>
            <li class="mt-4">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-3">Assistance</div>

                <button type="button" onclick="document.getElementById('submenu-tickets').classList.toggle('hidden')" class="w-full group flex items-center justify-between p-3.5 rounded-xl transition-all border {{ request()->routeIs('tickets.*') ? 'bg-blue-50 border-blue-200 text-blue-700 shadow-sm' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 transition duration-75 {{ request()->routeIs('tickets.*') ? 'text-blue-600' : 'text-slate-500 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="ml-3 font-bold text-[13px]">Tickets</span>
                    </div>
                    <svg class="w-4 h-4 {{ request()->routeIs('tickets.*') ? 'text-blue-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <ul id="submenu-tickets" class="mt-2 space-y-1 pl-11 pr-2 {{ request()->routeIs('tickets.*') ? '' : 'hidden' }}">
                    <li>
                        <a href="{{ route('tickets.index') }}" class="flex items-center justify-between p-2.5 rounded-lg transition-all {{ request()->routeIs('tickets.index') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 hover:text-blue-600' }}">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-list w-4 text-center"></i>
                                <span class="text-[11px] font-black uppercase tracking-wider">All Tickets</span>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tickets.create') }}" class="flex items-center justify-between p-2.5 rounded-lg transition-all {{ request()->routeIs('tickets.create') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-100 hover:text-blue-600' }}">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-plus w-4 text-center"></i>
                                <span class="text-[11px] font-black uppercase tracking-wider">Create Ticket</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
          </ul>
       </div>
    </aside>

    <div class="p-4 sm:p-6 sm:ml-72 mt-14 sm:mt-20 page-transition relative z-10">
        @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false });
                });
            </script>
        @endif
        @yield('content')
    </div>
</body>
</html> --}}
