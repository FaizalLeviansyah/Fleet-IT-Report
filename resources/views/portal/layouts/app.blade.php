<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITSM Portal - PT ASM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; } </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800">

    <aside class="w-64 bg-[#0F172A] text-slate-300 flex flex-col shadow-2xl transition-all duration-300 z-20">
        <div class="h-16 flex items-center px-6 border-b border-slate-700/50 bg-[#0B1120]">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center text-white font-black shadow-lg shadow-blue-500/30">IT</div>
                <span class="text-white font-bold text-lg tracking-wide">ITSM Stack</span>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5">
            <p class="px-2 text-[10px] font-extrabold text-slate-500 uppercase tracking-widest mb-3">Menu Utama</p>
            
            <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('portal.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-chart-pie w-5 text-center"></i>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            
            <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('portal.profile') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-user-circle w-5 text-center"></i>
                <span class="font-semibold text-sm">My Profile</span>
            </a>

            <a href="{{ route('portal.support') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('portal.support') ? 'bg-blue-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-life-ring w-5 text-center"></i>
                <span class="font-semibold text-sm">IT Support (Tickets)</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-700/50 bg-[#0B1120]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white uppercase">
                    {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->full_name }}</p>
                    <p class="text-[10px] text-slate-400 truncate uppercase">{{ Auth::user()->jabatan ?? Auth::user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 shadow-sm z-10">
            <div class="text-lg font-bold text-slate-700">@yield('page_title', 'Portal Karyawan')</div>
            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sm font-bold text-red-500 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-full transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>