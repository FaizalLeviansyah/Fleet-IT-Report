<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Fleet Report</title>

    <link rel="icon" type="image/jpg" href="{{ asset('public\img\Logo_PT_ASM.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .animated-bg {
            background: linear-gradient(-45deg, #f1f5f9, #e0f2fe, #f0f9ff, #ffffff);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .glass-card {
            background: rgba(255, 255, 255, 0.25); backdrop-filter: blur(50px) saturate(200%); -webkit-backdrop-filter: blur(50px) saturate(200%);
            border: 1px solid rgba(255, 255, 255, 0.6); border-top: 1px solid rgba(255, 255, 255, 0.8); border-left: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.1) inset, 0 0 50px -10px rgba(6, 182, 212, 0.3);
        }
        .glass-input { background: rgba(255, 255, 255, 0.4); border: 1px solid rgba(255, 255, 255, 0.5); transition: all 0.3s ease; }
        .glass-input:focus { background: rgba(255, 255, 255, 0.8); border-color: #06b6d4; box-shadow: 0 0 15px rgba(6, 182, 212, 0.2); }
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 5s ease-in-out infinite; }
        @keyframes blob-bounce { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(30px, -50px) scale(1.2); } 66% { transform: translate(-20px, 20px) scale(0.8); } }
        .animate-blob { animation: blob-bounce 10s infinite alternate cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="animated-bg min-h-screen flex items-center justify-center relative overflow-hidden px-4">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-10 w-96 h-96 bg-cyan-400/40 rounded-full blur-[90px] animate-blob mix-blend-multiply"></div>
        <div class="absolute bottom-0 right-10 w-96 h-96 bg-blue-500/40 rounded-full blur-[90px] animate-blob animation-delay-2000 mix-blend-multiply" style="animation-delay: 2s"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-400/30 rounded-full blur-[90px] animate-blob animation-delay-4000 mix-blend-multiply" style="animation-delay: 4s"></div>
    </div>

    <div class="relative z-10 w-full max-w-[420px]">
        <div class="glass-card rounded-[2.5rem] p-8 sm:p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/40 to-transparent pointer-events-none"></div>

            <div class="text-center mb-8 relative z-10">
                <div class="mb-6 flex justify-center relative z-10">
                    <img src="{{ asset('images/Logo_PT_ASM.jpg') }}" alt="PT Amarin" class="w-24 h-24 object-contain bg-white rounded-2xl shadow-lg ring-4 ring-white/50 animate-float">
                </div>

                <h2 class="text-3xl font-black text-slate-800 tracking-tighter drop-shadow-sm">
                    FLEET <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-500 to-blue-600">REPORT</span>
                </h2>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-2">PT Amarin Ship Management</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-5 relative z-10">
                @csrf
                @if($errors->any())
                    <div class="p-3 bg-red-500/10 border border-red-500/20 rounded-2xl flex items-center gap-3 text-red-600 text-xs font-bold backdrop-blur-md shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $errors->first('email_work') }}
                    </div>
                @endif
                <div>
                    <label class="block mb-2 text-[10px] font-extrabold uppercase text-slate-500 tracking-widest ml-2">Email Work</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-cyan-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" name="email_work" required class="glass-input w-full pl-12 pr-4 py-3.5 rounded-2xl text-sm text-slate-800 font-bold placeholder-slate-400 outline-none" placeholder="it.staff@amarin.com">
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-extrabold uppercase text-slate-500 tracking-widest ml-2">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400 group-focus-within:text-cyan-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" required class="glass-input w-full pl-12 pr-4 py-3.5 rounded-2xl text-sm text-slate-800 font-bold placeholder-slate-400 outline-none" placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="w-full group relative flex justify-center items-center gap-2 py-4 px-4 border border-transparent text-sm font-black rounded-2xl text-white bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 focus:outline-none focus:ring-4 focus:ring-cyan-200 shadow-xl shadow-cyan-500/30 transition-all transform hover:-translate-y-1 active:scale-95 mt-6 tracking-wide">
                    SECURE LOGIN
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>
            </form>
            <div class="mt-8 text-center pt-6 border-t border-slate-300/30 relative z-10">
                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">© {{ date('Y') }} PT Amarin Ship Management</p>
            </div>
        </div>
    </div>
</body>
</html>
