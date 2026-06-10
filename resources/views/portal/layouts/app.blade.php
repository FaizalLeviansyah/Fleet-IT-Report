<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'ITSM System') - PT Amarin Ship Management</title>
    
    <!-- Fonts: Plus Jakarta Sans (Sesuai HRIS) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/Logo_PT_ASM.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons: FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js (Untuk Interaksi Dropdown & Toggle) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        amarin: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 800: '#1e40af', 900: '#1e3a8a' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; } /* Soft Slate Background */
        [x-cloak] { display: none !important; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<!-- Alpine Data untuk state Global (Dark Mode & Compact Mode) -->
<body x-data="{ darkMode: false, compactMode: false, mobileMenuOpen: false }" 
      :class="{ 'dark bg-slate-900': darkMode, 'bg-slate-50': !darkMode }" 
      class="text-slate-800 transition-colors duration-300 h-screen overflow-hidden flex">

    <!-- ========================================== -->
    <!-- SIDEBAR (KIRI) -->
    <!-- ========================================== -->
    <aside class="w-[280px] bg-white border-r border-slate-200 flex flex-col h-screen fixed lg:relative z-50 transition-transform duration-300 transform lg:translate-x-0"
           :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Logo Area -->
        <div class="h-20 flex items-center px-6 pt-4 pb-2">
            <div class="h-20 flex items-center px-6 pt-4 pb-2">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/Logo_PT_ASM.jpg') }}" alt="Amarin Logo" class="w-10 h-10 rounded-xl shadow-md object-cover border border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[#031E49] font-black text-lg leading-tight tracking-wide">ITSM</span>
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">IT Information System</span>
                </div>
            </div>
        </div>
            <!-- Close mobile menu button -->
            <button @click="mobileMenuOpen = false" class="lg:hidden ml-auto text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>

        <!-- Sidebar Search -->
        <div class="px-6 py-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400 text-sm"></i>
                <input type="text" placeholder="Search menu..." class="w-full pl-10 pr-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-600 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder-slate-400">
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 pb-6 space-y-1">
            
            <!-- Dashboard (Active State Style) -->
            <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('portal.dashboard') ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="fas fa-home w-5 text-center"></i> Dashboard
            </a>

            <!-- Accordion: IT Support -->
            <div x-data="{ open: true }" class="mt-2">
                <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-slate-50/80 text-slate-800 rounded-xl font-bold text-sm hover:bg-slate-100 transition border border-slate-100">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-headset w-5 text-center text-slate-500"></i> IT Support
                    </div>
                    <i class="fas fa-chevron-down text-xs text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-collapse class="pl-12 pr-4 py-2 space-y-1">
                    <a href="{{ route('portal.support') }}" class="block text-sm font-semibold py-2 transition-colors {{ request()->routeIs('portal.support') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-800' }}">My Tickets</a>
                    <a href="{{ route('portal.create-ticket') }}" class="block text-sm font-semibold py-2 transition-colors {{ request()->routeIs('portal.create-ticket') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-800' }}">Create Ticket</a>
                    <a href="{{ route('portal.kb') }}" class="block text-sm font-semibold py-2 transition-colors {{ request()->routeIs('portal.kb') ? 'text-blue-600' : 'text-slate-500 hover:text-slate-800' }}">Knowledge Base</a>
                </div>
            </div>

            <!-- Single Menu: Assets -->
            <a href="{{ route('portal.assets') ?? '#' }}" class="mt-2 flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('portal.assets') ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                <i class="fas fa-desktop w-5 text-center"></i> My IT Assets
            </a>

            <div class="pt-4 mt-4 border-t border-slate-200 space-y-1">
                <p class="px-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-2">Personal & Access</p>
                
                <a href="{{ route('portal.my-access') ?? '#' }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('portal.my-access') ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fas fa-fingerprint w-5 text-center"></i> My Access & VPN
                </a>
                
                <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all {{ request()->routeIs('portal.profile') ? 'bg-[#1e3a8a] text-white shadow-md' : 'text-slate-600 hover:bg-slate-50' }}">
                    <i class="fas fa-user-circle w-5 text-center"></i> My Profile
                </a>
            </div>
        </nav>

        <!-- Logout Button (Bottom) -->
        <div class="p-6 border-t border-slate-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 bg-red-50 text-red-600 rounded-xl font-bold text-sm hover:bg-red-100 transition-colors border border-red-100">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" x-cloak class="fixed inset-0 bg-slate-900/50 z-40 lg:hidden backdrop-blur-sm"></div>

    <!-- ========================================== -->
    <!-- MAIN CONTENT AREA (KANAN) -->
    <!-- ========================================== -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- TOP NAVBAR -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-6 lg:px-8 z-30" :class="compactMode ? 'h-16' : 'h-20'">
            
            <!-- Left: Mobile Toggle & Page Title -->
            <div class="flex items-center gap-4">
                <button @click="mobileMenuOpen = true" class="lg:hidden w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-full text-slate-600 shadow-sm">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center gap-3 lg:gap-4">
                
                <!-- 1. Global Search (Ctrl+K) -->
                <div class="relative hidden md:block group">
                    <i class="fas fa-search absolute left-3.5 top-2.5 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input type="text" placeholder="Search..." class="pl-10 pr-16 py-2 bg-white border border-slate-200 rounded-full text-sm font-medium w-[260px] focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none shadow-sm transition-all">
                    <div class="absolute right-2 top-1.5 flex items-center gap-1">
                        <span class="text-[10px] font-extrabold text-slate-400 bg-slate-100 border border-slate-200 px-2 py-1 rounded-md tracking-widest">CTRL+K</span>
                    </div>
                </div>

                <!-- 2. Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-full text-slate-600 hover:bg-slate-50 hover:text-blue-600 shadow-sm transition-colors">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <!-- 3. Display Preferences Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-full text-slate-600 hover:bg-slate-50 hover:text-blue-600 shadow-sm transition-colors" :class="open ? 'bg-slate-50 ring-2 ring-slate-100' : ''">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                    
                    <!-- Preferences Menu -->
                    <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-72 bg-white border border-slate-200 rounded-2xl shadow-xl py-4 z-50">
                        <div class="px-5 mb-3">
                            <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest">Display Preferences</span>
                        </div>
                        
                        <!-- Toggle Compact Mode -->
                        <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50 cursor-pointer" @click="compactMode = !compactMode">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Compact Mode</p>
                                <p class="text-xs text-slate-500 font-medium">Reduce spacing & padding</p>
                            </div>
                            <!-- Toggle Switch UI -->
                            <div class="w-10 h-6 rounded-full flex items-center p-1 transition-colors duration-300" :class="compactMode ? 'bg-blue-500' : 'bg-slate-200'">
                                <div class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-300" :class="compactMode ? 'translate-x-4' : 'translate-x-0'"></div>
                            </div>
                        </div>

                        <!-- Toggle Reduce Motion -->
                        <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50 cursor-pointer">
                            <div>
                                <p class="text-sm font-bold text-slate-800">Reduce Motion</p>
                                <p class="text-xs text-slate-500 font-medium">Disable animations</p>
                            </div>
                            <div class="w-10 h-6 rounded-full bg-slate-200 flex items-center p-1">
                                <div class="w-4 h-4 bg-white rounded-full shadow-sm"></div>
                            </div>
                        </div>
                        <div class="px-5 pt-3 mt-2 border-t border-slate-100">
                            <p class="text-xs font-medium text-slate-400">Preferences are saved locally.</p>
                        </div>
                    </div>
                </div>

                <!-- 4. Active Tickets Badge (Adaptasi dari 'Pending Approvals') -->
                <a href="{{ route('portal.support') }}" class="hidden md:flex items-center gap-3 bg-[#FFFbeb] border border-[#FDE68A] px-3 py-1.5 rounded-xl shadow-sm hover:bg-[#FEF3C7] transition-colors cursor-pointer">
                    <div class="w-7 h-7 bg-[#F59E0B] text-white rounded-lg flex items-center justify-center text-xs font-black shadow-sm">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-extrabold text-[#92400E] leading-tight tracking-wide">ACTIVE TICKETS</span>
                        <span class="text-[11px] font-bold text-[#D97706] leading-tight flex items-center gap-1">
                            <i class="fas fa-circle text-[6px]"></i> Check Status
                        </span>
                    </div>
                </a>

                <!-- 5. Notifications Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="w-10 h-10 flex items-center justify-center bg-white border border-slate-200 rounded-full text-slate-600 hover:bg-slate-50 shadow-sm relative">
                        <i class="fas fa-bell"></i>
                        <!-- Red Badge -->
                        <span class="absolute -top-1.5 -right-1.5 bg-red-600 text-white text-[10px] font-extrabold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">2</span>
                    </button>

                    <!-- Notif Menu -->
                    <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-80 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden z-50">
                        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2"><i class="fas fa-bell text-blue-500"></i> Notifications</h3>
                            <button class="text-xs font-bold text-blue-600 hover:text-blue-800">Mark read</button>
                        </div>
                        <div class="max-h-80 overflow-y-auto p-2">
                            <!-- Notif Item 1 -->
                            <div class="p-3 hover:bg-slate-50 rounded-xl cursor-pointer transition-colors flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-comment-dots"></i>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-extrabold text-blue-600 uppercase tracking-wider">IT SUPPORT</span>
                                        <span class="text-[10px] font-semibold text-slate-400">10m ago</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">Teknisi membalas tiket Anda</p>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">"Baik pak, tim kami sedang menuju ke ruangan lantai 8..."</p>
                                </div>
                            </div>
                            <!-- Notif Item 2 -->
                            <div class="p-3 hover:bg-slate-50 rounded-xl cursor-pointer transition-colors flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-1">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider">SYSTEM</span>
                                        <span class="text-[10px] font-semibold text-slate-400">1d ago</span>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">Tiket INC-2024 diselesaikan</p>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">Masalah jaringan telah diperbaiki. Silakan berikan approval.</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-t border-slate-100 text-center bg-slate-50/50">
                            <button class="text-sm font-bold text-blue-600 hover:text-blue-800"><i class="fas fa-chevron-down mr-1"></i> Load more</button>
                        </div>
                    </div>
                </div>

                <!-- 6. User Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 bg-white border border-slate-200 pl-2 pr-4 py-1.5 rounded-full hover:bg-slate-50 shadow-sm transition-all" :class="open ? 'ring-2 ring-blue-100 border-blue-300' : ''">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-sm border border-blue-200">
                            {{ substr(Auth::user()->full_name ?? 'U', 0, 1) }}
                        </div>
                        <span class="text-sm font-bold text-slate-700 hidden md:block">{{ Auth::user()->full_name ?? 'User' }}</span>
                        <i class="fas fa-chevron-down text-slate-400 text-[10px] hidden md:block transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Profile Menu -->
                    <div x-show="open" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-56 bg-white border border-slate-200 rounded-2xl shadow-xl py-2 z-50 overflow-hidden">
                        <div class="px-5 py-3 border-b border-slate-100 mb-2">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->full_name }}</p>
                            <p class="text-xs font-medium text-slate-500 truncate">{{ Auth::user()->email_work }}</p>
                        </div>
                        
                        <a href="{{ route('portal.profile') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-user-circle w-5 text-center text-slate-400"></i> My Profile
                        </a>
                        <a href="#" class="flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                            <i class="fas fa-key w-5 text-center text-slate-400"></i> Change Password
                        </a>
                        
                        <div class="border-t border-slate-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- MAIN CONTENT CONTAINER -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 transition-all" :class="compactMode ? 'p-4' : 'p-6 lg:p-8'">
            @yield('content')
        </main>
        
    </div>

</body>
</html>