<div class="amarin-login-wrapper">

    <div class="amarin-login-left">
        <div class="amarin-ornament-1"></div>
        <div class="amarin-ornament-2"></div>
        <div class="amarin-ornament-3"></div>

        <div class="amarin-logo-top">
            <span>AMARIN SHIP MANAGEMENT</span>
            <img src="/img/Logo_PT_ASM.jpg" alt="Amarin Logo" class="h-8 w-auto relative z-10" onerror="this.style.display='none'">
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
        <div class="bg-grid"></div>

        <div class="amarin-login-card">
            <div class="card-header">
                <div class="shining-logo">
                    <img src="/img/Logo_PT_ASM.jpg" alt="Amarin Logo">
                </div>
                <h2>ITSM Portal</h2>
                <p class="subtitle">Secure Tech Support Access</p>
                <p class="respect">R.E.S.P.E.C.T</p>
            </div>

            <form wire:submit="authenticate" class="amarin-form">
                {{ $this->form }}

                <button type="submit" class="btn-submit" id="submitBtn">
                    Sign In
                    <svg class="btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>

        <p class="footer-text">&copy; {{ date('Y') }} AMARIN SHIP MANAGEMENT</p>
    </div>

    <style>
        /* POSISI ROW-REVERSE AGAR BIRU DI KANAN, PUTIH DI KIRI */
        .amarin-login-wrapper { display: flex; flex-direction: row-reverse; position: fixed; inset: 0; width: 100vw; height: 100vh; background-color: #fafbfc; z-index: 99999; font-family: 'Poppins', sans-serif; overflow: hidden; }

        /* --- PANEL BIRU (RATA KANAN) --- */
        .amarin-login-left { display: none; background-color: #031E49; width: 55%; position: relative; flex-direction: column; justify-content: space-between; align-items: flex-end; text-align: right; padding: 4rem; overflow: hidden; color: white; }
        @media (min-width: 1024px) { .amarin-login-left { display: flex; } }

        .amarin-ornament-1 { position: absolute; top: -6rem; left: -6rem; width: 24rem; height: 24rem; border-radius: 50%; border: 40px solid rgba(59, 130, 246, 0.1); pointer-events: none; }
        .amarin-ornament-2 { position: absolute; top: 40%; right: -10%; width: 30rem; height: 30rem; border-radius: 50%; border: 2px solid rgba(34, 211, 238, 0.15); pointer-events: none; }
        .amarin-ornament-3 { position: absolute; top: 45%; right: 5%; width: 20rem; height: 20rem; border-radius: 50%; border: 1px solid rgba(34, 211, 238, 0.1); pointer-events: none; }

        .amarin-logo-top { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; position: relative; z-index: 10; }
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

        /* --- PANEL PUTIH (FORM LOGIN) --- */
        .amarin-login-right { width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative; background-color: #fafbfc; }
        @media (min-width: 1024px) { .amarin-login-right { width: 45%; } }

        .bg-grid { position: absolute; inset: 0; opacity: 0.03; background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px; pointer-events: none; }

        .amarin-login-card { width: 100%; max-width: 420px; background-color: white; padding: 2.5rem 2.5rem; border-radius: 1.5rem; border: 1px solid rgba(243, 244, 246, 0.8); position: relative; z-index: 10; margin: 0 1rem; box-shadow: 0 20px 50px -20px rgba(0, 0, 0, 0.08); }

        .card-header { display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 2rem; }
        .shining-logo { position: relative; display: inline-block; overflow: hidden; border-radius: 0.5rem; margin-bottom: 1.25rem; }
        .shining-logo img { height: 4.5rem; width: auto; position: relative; z-index: 10; }
        .shining-logo::after { content: ''; position: absolute; top: 0; left: -150%; width: 100%; height: 100%; background: linear-gradient(to right, transparent, rgba(255,255,255,0.7), transparent); transform: skewX(-25deg); animation: shine 3s infinite ease-in-out; z-index: 20; }

        .card-header h2 { font-size: 1.625rem; font-weight: 800; color: #111827; margin-bottom: 0.25rem; letter-spacing: -0.025em; }
        .subtitle { font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.75rem; font-weight: 500; }
        .respect { font-size: 0.6rem; font-weight: 700; letter-spacing: 0.35em; color: #9ca3af; text-transform: uppercase; }

        .amarin-form { display: flex; flex-direction: column; gap: 1.5rem; width: 100%; }

        .btn-submit { width: 100%; padding: 0.85rem 1rem; margin-top: 1rem; background-color: #031E49; color: white; font-weight: 600; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.3s ease; box-shadow: 0 10px 15px -3px rgba(3, 30, 73, 0.2); border: none; cursor: pointer; font-size: 0.875rem; }
        .btn-submit:hover { background-color: #1e40af; }
        .btn-arrow { width: 1rem; height: 1rem; transition: transform 0.3s ease; }
        .btn-submit:hover .btn-arrow { transform: translateX(0.375rem); }

        .footer-text { position: absolute; bottom: 2rem; font-size: 0.7rem; color: #9ca3af; font-weight: 500; }

        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

        .fi-fo-field-wrp-label { font-size: 0.75rem !important; font-weight: 700 !important; color: #4b5563 !important; letter-spacing: 0.025em; text-transform: uppercase; }
        .fi-fo-checkbox { margin-top: 1rem !important; }
    </style>
</div>
