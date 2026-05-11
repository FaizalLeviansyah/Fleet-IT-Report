<!DOCTYPE html>
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
                  <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest -mt-0.5">Fleet Reporting</span>
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
</html>
