<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Karyawan - PT ASM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <nav class="bg-white shadow-sm border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-6">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black text-xl px-4 py-1.5 rounded-xl shadow-md">ITSM</div>
            <a href="{{ route('portal.dashboard') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">Dashboard Saya</a>
            <a href="{{ route('portal.kb') }}" class="font-bold text-slate-500 hover:text-blue-600 transition">Pusat Bantuan</a>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-bold text-sm bg-slate-100 px-4 py-2 rounded-full">{{ Auth::user()->full_name ?? Auth::user()->email_work }}</span>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 font-extrabold text-sm hover:underline bg-red-50 px-4 py-2 rounded-full">
                    <i class="fas fa-power-off"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6 mt-4">
        @yield('content')
    </main>
</body>
</html>