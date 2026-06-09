<div class="amarin-login-wrapper">

    <div class="amarin-login-left" wire:ignore>
        <div class="amarin-ornament-1"></div>
        <div class="amarin-ornament-2"></div>
        <div class="amarin-ornament-3"></div>

        <div class="amarin-logo-top">
            <span>PT AMARIN SHIP MANAGEMENT</span>
            <div class="relative group flex items-center justify-center p-1.5 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-[0_0_20px_rgba(59,130,246,0.3)]">
                <div class="absolute inset-0 bg-blue-400 opacity-30 blur-md rounded-xl"></div>
                <img src="/img/Logo_PT_ASM.jpg" alt="Amarin Logo" class="h-8 w-auto relative z-10 rounded-lg">
            </div>
        </div>

        <div class="amarin-hero-text">
            <h1>Modern ITSM for<br><span class="text-gradient">Global Fleet</span></h1>
            <p>Empowering your IT workforce with secure, intelligent, and seamless access to enterprise tech support tools.</p>

            <div class="amarin-pills">
                <span class="amarin-pill"><span class="dot dot-green"></span> Ticketing</span>
                <span class="amarin-pill"><span class="dot dot-blue"></span> Asset Management</span>
                <span class="amarin-pill"><span class="dot dot-yellow"></span> Live Radar</span>
            </div>
        </div>

        <div class="amarin-culture">
            <h3>Our Culture</h3>
            <div class="culture-boxes">
                <span class="c-box">R</span><span class="c-box">E</span><span class="c-box">S</span>
                <span class="c-box">P</span><span class="c-box">E</span><span class="c-box">C</span><span class="c-box">T</span>
            </div>
            <p>Responsible &middot; Ethic &middot; Safety &middot; People &middot; Environment &middot; Care &middot; Trust</p>
        </div>
    </div>

    <div class="amarin-login-right">
        <div class="bg-grid" wire:ignore></div>

        <div class="amarin-login-card">
            <div class="card-header" wire:ignore>
                <div class="shining-logo">
                    <img src="/img/Logo_PT_ASM.jpg" alt="Amarin Logo">
                </div>
                <h2>ITSM Portal</h2>
                <p class="subtitle">Secure Tech Support Access</p>
                <p class="respect">R.E.S.P.E.C.T</p>
            </div>

            <!-- 👇 TAMBAHAN DROPDOWN ALA GLPI 👇 -->
            <div class="login-destination-wrapper" wire:ignore>
                <label class="destination-label">Login Destination </label>
                <div class="destination-select-container">
                    <select class="destination-select">
                        <option value="auto">🤖 Auto-Detect Route (Recomennded)</option>
                        <option value="admin">👑 IT Admin Panel</option>
                        <option value="portal">💼 Employee or Vessel Crew</option>
                    </select>
                    <div class="select-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <p class="destination-hint">*Sistem akan mengarahkan Anda otomatis berdasarkan hak akses.</p>
            </div>
            <!-- 👆 AKHIR TAMBAHAN 👆 -->

            <form wire:submit="authenticate" class="amarin-form">

                {{ $this->form }}

                <div class="forgot-password-container">
                    {{ $this->forgotPasswordAction }}
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    Sign In
                    <svg style="width: 18px; height: 18px; transition: transform 0.3s;" class="btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>

        <p class="footer-text" wire:ignore>&copy; {{ date('Y') }} PT AMARIN SHIP MANAGEMENT</p>
    </div>

    <x-filament-actions::modals />

    <style>
        .amarin-login-wrapper { display: flex; flex-direction: row-reverse; position: fixed; inset: 0; width: 100vw; height: 100vh; background-color: #FAFBFC; z-index: 99999; font-family: 'Poppins', sans-serif; overflow: hidden; }

        .amarin-login-left { display: none; background-color: #031E49; width: 55%; position: relative; flex-direction: column; justify-content: space-between; align-items: flex-end; text-align: right; padding: 4rem; overflow: hidden; color: white; }
        @media (min-width: 1024px) { .amarin-login-left { display: flex; } }

        .amarin-ornament-1 { position: absolute; top: -6rem; left: -6rem; width: 24rem; height: 24rem; border-radius: 50%; border: 40px solid rgba(59, 130, 246, 0.1); pointer-events: none; }
        .amarin-ornament-2 { position: absolute; top: 40%; right: -10%; width: 30rem; height: 30rem; border-radius: 50%; border: 2px solid rgba(34, 211, 238, 0.15); pointer-events: none; }
        .amarin-ornament-3 { position: absolute; top: 45%; right: 5%; width: 20rem; height: 20rem; border-radius: 50%; border: 1px solid rgba(34, 211, 238, 0.1); pointer-events: none; }

        .amarin-logo-top { display: flex; align-items: center; justify-content: flex-end; gap: 1rem; position: relative; z-index: 10; }
        .amarin-logo-top span { font-weight: 600; font-size: 0.875rem; letter-spacing: 0.025em; color: #eff6ff; }

        .amarin-hero-text { position: relative; z-index: 10; margin-top: 2.5rem; max-width: 42rem; display: flex; flex-direction: column; align-items: flex-end; }
        .amarin-hero-text h1 { font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -0.025em; }
        .text-gradient { background: linear-gradient(to right, #67e8f9, #93c5fd); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .amarin-hero-text p { font-size: 1.125rem; color: rgba(219, 234, 254, 0.7); line-height: 1.625; margin-bottom: 2.5rem; max-width: 36rem; font-weight: 300; }

        .amarin-pills { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 0.75rem; margin-bottom: 2.5rem; }
        .amarin-pill { padding: 0.625rem 1.25rem; border-radius: 9999px; border: 1px solid rgba(59, 130, 246, 0.3); background-color: rgba(6, 40, 92, 0.8); backdrop-filter: blur(4px); font-size: 0.75rem; font-weight: 600; display: flex; align-items: center; gap: 0.625rem; color: #dbeafe; }
        .dot { width: 0.5rem; height: 0.5rem; border-radius: 50%; }
        .dot-green { background-color: #34d399; animation: pulse 2s infinite; }
        .dot-blue { background-color: #60a5fa; }
        .dot-yellow { background-color: #fbbf24; }

        .amarin-culture { position: relative; z-index: 10; margin-bottom: 1rem; display: flex; flex-direction: column; align-items: flex-end; }
        .amarin-culture h3 { font-size: 0.625rem; font-weight: 700; letter-spacing: 0.2em; color: rgba(147, 197, 253, 0.5); text-transform: uppercase; margin-bottom: 0.75rem; }
        .culture-boxes { display: flex; justify-content: flex-end; gap: 0.375rem; margin-bottom: 0.5rem; }
        .c-box { width: 2rem; height: 2rem; border-radius: 0.375rem; background-color: #06285c; border: 1px solid rgba(59, 130, 246, 0.3); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.875rem; color: #eff6ff; }
        .amarin-culture p { font-size: 0.625rem; color: rgba(191, 219, 254, 0.5); font-weight: 500; letter-spacing: 0.025em; margin-top: 0.75rem; }

        /* AREA KANAN (FORM) */
        .amarin-login-right { width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; background-color: #FAFBFC; }
        @media (min-width: 1024px) { .amarin-login-right { width: 45%; } }
        .bg-grid { position: absolute; inset: 0; opacity: 0.03; background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px; pointer-events: none; }

      /* SHADOW CARD MEWAH & SUPER TEGAS */
        .amarin-login-card { width: 100%; max-width: 420px; background-color: white; padding: 2.5rem 2.5rem; border-radius: 1.25rem; border: 1px solid rgba(15, 29, 61, 0.1); position: relative; z-index: 10; margin: 0 1rem; box-shadow: 0 25px 50px -12px rgba(3, 30, 73, 0.35), 0 0 0 1px rgba(3, 30, 73, 0.05) !important; transition: all 0.3s ease; }
        .amarin-login-card:hover { transform: translateY(-3px); box-shadow: 0 30px 60px -10px rgba(3, 30, 73, 0.45) !important; }

        .card-header { display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 2rem; }
        .shining-logo { position: relative; display: inline-block; overflow: hidden; border-radius: 0.5rem; margin-bottom: 1rem; }
        .shining-logo img { height: 4rem; width: auto; position: relative; z-index: 10; border-radius: 0.5rem; }
        .shining-logo::after { content: ''; position: absolute; top: 0; left: -150%; width: 100%; height: 100%; background: linear-gradient(to right, transparent, rgba(255,255,255,0.7), transparent); transform: skewX(-25deg); animation: shine 3s infinite ease-in-out; z-index: 20; }
        .card-header h2 { font-size: 1.75rem; font-weight: 800; color: #111827; margin-bottom: 0.2rem; letter-spacing: -0.025em; }
        .subtitle { font-size: 0.85rem; color: #4B5563; margin-bottom: 0.75rem; font-weight: 500; }
        .respect { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.35em; color: #9CA3AF; text-transform: uppercase; }

        /* STYLING DROPDOWN */
        .login-destination-wrapper { margin-bottom: 1.25rem; width: 100%; }
        .destination-label { display: block; font-size: 11px; font-weight: 700; color: #4B5563; letter-spacing: 0.025em; text-transform: uppercase; margin-bottom: 0.5rem; }
        .destination-select-container { position: relative; width: 100%; }
        .destination-select { appearance: none; -webkit-appearance: none; width: 100%; border-radius: 0.5rem; border: 1px solid #D1D5DB; padding: 0.5rem 2.5rem 0.5rem 1rem; font-size: 0.875rem; color: #374151; background-color: #F9FAFB; font-weight: 600; cursor: pointer; transition: all 0.2s ease; outline: none; }
        .destination-select:focus { border-color: #3B82F6; box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2); background-color: white; }
        .select-icon { position: absolute; inset-y: 0; right: 0; display: flex; align-items: center; padding-right: 0.75rem; pointer-events: none; color: #6B7280; }
        .select-icon svg { width: 1.25rem; height: 1.25rem; }
        .destination-hint { font-size: 0.65rem; color: #9CA3AF; margin-top: 0.375rem; font-weight: 500; }

        /* FORM & BUTTON */
        .amarin-form { display: flex; flex-direction: column; width: 100%; gap: 1.25rem; }

        /* Mengakali Posisi Forgot Password agar sejajar dengan Checkbox */
        .forgot-password-container { text-align: right; margin-top: -38px; position: relative; z-index: 20; height: 0; overflow: visible; }
        .forgot-password-container a { font-size: 0.75rem !important; color: #2563EB !important; text-decoration: none !important; font-weight: 600 !important; }
        .forgot-password-container a:hover { color: #1D4ED8 !important; text-decoration: underline !important; }

        .btn-submit { width: 100%; padding: 0.75rem 1rem; margin-top: 1.5rem; background-color: #0056B3; color: white; font-weight: 600; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; border: none; cursor: pointer; font-size: 0.875rem; box-shadow: 0 4px 6px -1px rgba(0, 86, 179, 0.2); }
        .btn-submit:hover { background-color: #004494; box-shadow: 0 6px 8px -1px rgba(0, 86, 179, 0.3); }
        .btn-submit:hover .btn-arrow { transform: translateX(4px); }

        .footer-text { position: absolute; bottom: 2rem; font-size: 0.7rem; color: #9CA3AF; font-weight: 500; }

        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }

        /* 👇 FIX: MERAMPIKAN LABEL FORM FILAMENT AGAR TIDAK BESAR & JELEK 👇 */
        .fi-fo-field-wrp-label { font-size: 11px !important; font-weight: 700 !important; color: #4B5563 !important; letter-spacing: 0.025em; text-transform: uppercase !important; }
        .fi-input { border-radius: 0.5rem !important; border-color: #D1D5DB !important; box-shadow: none !important; padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
        .fi-input:focus { border-color: #3B82F6 !important; ring: 2px solid #BFDBFE !important; }
        .fi-fo-checkbox { margin-top: 0.25rem !important; }
        .fi-checkbox-input { border-radius: 0.25rem !important; }
    </style>
</div>