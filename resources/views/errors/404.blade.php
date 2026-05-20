<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Kapal Keluar Jalur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .animate-float { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen font-sans selection:bg-blue-500 selection:text-white">

    <div class="text-center px-6">
        <div class="relative flex justify-center items-center mb-8 animate-float">
            <h1 class="text-[150px] font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-600 to-cyan-400 drop-shadow-xl opacity-20 relative z-0">
                404
            </h1>
            <div class="absolute z-10 text-7xl">🚢</div>
        </div>

        <h2 class="mt-4 text-3xl font-extrabold text-slate-800 tracking-tight">Waduh! Kapal Keluar Jalur.</h2>
        <p class="mt-3 text-slate-500 max-w-md mx-auto text-sm leading-relaxed">
            Halaman atau tiket yang Anda cari di Sistem Manajemen Amarin tidak dapat ditemukan, sudah berlabuh, atau Anda tidak memiliki izin untuk mengaksesnya.
        </p>

        <a href="/admin" class="mt-8 inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-500 rounded-full hover:scale-105 transition-all duration-300 shadow-lg shadow-blue-500/40">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Command Center
        </a>
    </div>

</body>
</html>
