<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent; // 👇 SUDAH DIKOREKSI DI SINI! 👇
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\MenuItem;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // TAB BROWSER & FAVICON
            ->brandName('ITSM Stack')
            ->favicon(asset('img/Logo_PT_ASM.jpg'))
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->spa()

            //👇 WARNA UTAMA TEMA (Kita pakai Biru) 👇
            ->colors(['primary' => Color::Blue])
            ->font('Poppins')
            ->brandLogo(fn () => view('filament.components.logo'))

            //👇 LONCENG NOTIFIKASI REAL-TIME (Bubble diaktifkan 30 detik) 👇
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')

            // MENU PROFIL KUSTOM (Memicu Modal Edit Profile)
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile Settings')
                    ->url('javascript:window.dispatchEvent(new Event(\'open-profile-modal\'))')
                    ->icon('heroicon-o-cog-8-tooth'),
            ])
            // 👇 HOOK: RENDER MODAL PROFIL & CEK PASSWORD DEFAULT 👇
            // 👇 HOOK: RENDER MODAL PROFIL & LAYAR KUNCI SEJATI 👇
            ->renderHook('panels::body.end', function (): string {
                return \Illuminate\Support\Facades\Blade::render('
                    @livewire("edit-profile-modal")
                    @livewire("force-change-password")
                ');
            })

            // HOOK: LOADING SCREEN UNIVERSAL (INSTAN 0 DETIK!)
            ->renderHook(
                'panels::body.start',
                fn (): string => '
                <div id="amarin-global-loader" style="position: fixed; inset: 0; z-index: 999999; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 1; visibility: visible; transition: opacity 0.3s ease, visibility 0.3s ease;">
                    <div class="loader-container">
                        <div class="spinner-ring"></div>
                        <div class="spinner-ring-inner"></div>
                        <div class="logo-text">AMARIN</div>
                    </div>
                    <div class="loading-text">MEMUAT SISTEM...</div>
                </div>

                <script>
                    window.addEventListener("load", function() {
                        const loader = document.getElementById("amarin-global-loader");
                        if(loader) { loader.style.opacity = "0"; setTimeout(() => loader.style.visibility = "hidden", 100); }
                    });
                    document.addEventListener("livewire:navigating", function() {
                        const loader = document.getElementById("amarin-global-loader");
                        if(loader) { loader.style.visibility = "visible"; loader.style.opacity = "1"; }
                    });
                    document.addEventListener("livewire:navigated", function() {
                        const loader = document.getElementById("amarin-global-loader");
                        if(loader) { loader.style.opacity = "0"; setTimeout(() => loader.style.visibility = "hidden", 100); }
                    });
                </script>

                <style>
                    .loader-container { position: relative; display: flex; justify-content: center; align-items: center; width: 120px; height: 120px; }
                    .spinner-ring { position: absolute; width: 100px; height: 100px; border: 4px solid transparent; border-top-color: #2563EB; border-bottom-color: #06B6D4; border-radius: 50%; animation: spin 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; }
                    .spinner-ring-inner { position: absolute; width: 70px; height: 70px; border: 4px solid transparent; border-left-color: #1E3A8A; border-right-color: #3B82F6; border-radius: 50%; animation: spin-reverse 0.8s linear infinite; }
                    .logo-text { position: absolute; font-size: 15px; font-weight: 900; color: #1E3A8A; letter-spacing: 2px; }
                    .loading-text { margin-top: 24px; font-size: 13px; font-weight: 800; color: #2563EB; letter-spacing: 2px; animation: pulse 1.5s infinite; }
                    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                    @keyframes spin-reverse { 0% { transform: rotate(360deg); } 100% { transform: rotate(0deg); } }
                    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
                </style>
                '
            )

            // HOOK: CSS & PWA CHROME APP
            ->renderHook(
                'panels::head.end',
                fn (): string => '
                <link rel="manifest" href="/manifest.json">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <style>
                    /* 👇 SIDEBAR: KONSISTEN DENGAN LOGIN LEFT PANEL (DEEP BLUE #031E49) 👇 */
                    .fi-sidebar { background-color: #031E49 !important; border-right: none !important; box-shadow: 4px 0 15px rgba(0,0,0,0.05) !important; }
                    .fi-sidebar-header { background-color: #031E49 !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 1rem 1.5rem; }

                    /* Sidebar Items Default (Teks Biru Pucat) */
                    .fi-sidebar-item-button { transition: all 0.3s ease; border-radius: 0.5rem !important; margin: 0.25rem 0.8rem !important; padding: 0.6rem 1rem !important; }
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #93C5FD !important; font-weight: 500 !important; transition: all 0.3s ease; }

                    /* Hover Effect */
                    .fi-sidebar-item-button:hover { background-color: rgba(37, 99, 235, 0.15) !important; transform: translateX(4px); }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label, .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #ffffff !important; }

                    /* Active Effect (Biru HRIS Menyala) */
                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background: linear-gradient(90deg, #2563EB 0%, #031E49 100%) !important;
                        border-left: 4px solid #22D3EE !important;
                        border-radius: 0 0.5rem 0.5rem 0 !important;
                        margin-left: 0 !important;
                        padding-left: 1.55rem !important;
                        box-shadow: inset 15px 0 30px -15px rgba(34, 211, 238, 0.2) !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-label, .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: 700 !important; }

                    /* 👇 NAVBAR: GLASSMORPHISM PUTIH BERSIH 👇 */
                    .fi-topbar { background-color: rgba(255, 255, 255, 0.8) !important; border-bottom: 1px solid rgba(0,0,0,0.05) !important; padding: 0.5rem 1.5rem !important; height: auto !important; backdrop-filter: blur(12px) !important; -webkit-backdrop-filter: blur(12px) !important; position: sticky !important; top: 0; z-index: 40; transition: all 0.3s ease; }
                    .dark .fi-topbar { background-color: rgba(3, 30, 73, 0.8) !important; border-bottom: 1px solid rgba(255,255,255,0.05) !important; }
                    .fi-topbar > nav { background: transparent !important; gap: 0.5rem !important; align-items: center !important; }

                    /* BUBBLE LONCENG */
                    .fi-topbar-database-notifications-trigger .fi-icon-btn-badge { display: flex !important; align-items: center; justify-content: center; background-color: #EF4444 !important; color: white !important; font-size: 10px !important; font-weight: 800 !important; width: 18px !important; height: 18px !important; border-radius: 50% !important; top: -5px !important; right: -5px !important; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5) !important; border: 2px solid white !important; }

                    /* SHADOW CARD DASHBOARD */
                    .fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat { background-color: #ffffff !important; border: 1px solid rgba(0,0,0,0.05) !important; border-radius: 1.25rem !important; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01) !important; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important; }
                    .fi-section:hover, .fi-ta-ctn:hover, .fi-wi-stats-overview-stat:hover { transform: translateY(-3px); box-shadow: 0 20px 40px -10px rgba(3, 30, 73, 0.08) !important; border-color: rgba(3, 30, 73, 0.1) !important; }

                    /* SEMBUNYIKAN TEMA BAWAAN & TULISAN DASHBOARD */
                    .fi-user-menu .fi-theme-switcher { display: none !important; }
                    ' . (request()->routeIs('filament.admin.pages.dashboard') ? 'header.fi-header { display: none !important; }' : '') . '
                </style>'
            )

            // HOOKS LOGO & SEARCH (Seperti sebelumnya)
            ->renderHook('panels::sidebar.nav.start', fn (): string => view('filament.components.sidebar-search')->render())
            ->renderHook('panels::topbar.start', fn (): string => view('filament.components.navbar-search')->render())
            ->renderHook('panels::global-search.after', fn (): string => view('filament.components.topbar-actions')->render())

            // HOOK: INJEKSI FOTO & JABATAN PROFIL
            ->renderHook(
                'panels::user-menu.profile.before',
                function (): string {
                    $user = auth()->user();
                    $jabatan = $user->jabatan ?? 'IT Support Team';
                    $namaLengkap = $user->full_name ?? 'Administrator';

                    if ($user->avatar_url) {
                        $avatarHtml = '<img src="/storage/' . $user->avatar_url . '" style="width: 55px; height: 55px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3); margin-bottom: 10px; border: 2px solid #2563EB;">';
                    } else {
                        $inisial = strtoupper(substr($namaLengkap, 0, 1));
                        $avatarHtml = '<div style="width: 55px; height: 55px; border-radius: 50%; background: linear-gradient(135deg, #2563EB, #06B6D4); color: white; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: bold; margin-bottom: 10px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);">' . $inisial . '</div>';
                    }

                    return '
                    <div style="padding: 16px 12px; border-bottom: 1px solid #E5E7EB; display: flex; flex-direction: column; align-items: center; text-align: center;">
                        ' . $avatarHtml . '
                        <div style="font-size: 13px; font-weight: 700; color: #1F2937;">' . $namaLengkap . '</div>
                        <div style="font-size: 10px; font-weight: 800; color: #2563EB; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 4px;">' . $jabatan . '</div>
                    </div>';
                }
            )

            // HOOK: TOMBOL LOGOUT SIDEBAR BAWAH
            ->renderHook(
                'panels::sidebar.footer',
                fn (): string => '<div style="padding: 1rem; margin-top: auto;">
                    <form method="POST" action="'.route('filament.admin.auth.logout').'" style="margin: 0;">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="button"
                            onclick="Swal.fire({ title: \'Shutdown System?\', text: \'Sesi Anda akan diakhiri.\', icon: \'warning\', showCancelButton: true, confirmButtonColor: \'#ef4444\', cancelButtonColor: \'#64748b\', confirmButtonText: \'Ya, Shutdown!\', background: \'rgba(255,255,255,0.95)\', backdrop: \'rgba(15,23,42,0.7)\' }).then((result) => { if (result.isConfirmed) { this.closest(\'form\').submit(); } })"
                            style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 8px; padding: 8px 16px; font-size: 12px; font-weight: bold; color: white; background: linear-gradient(to right, #dc2626, #e11d48); border-radius: 8px; border: 1px solid #f87171; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.4);">
                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path></svg>
                            SHUTDOWN
                        </button>
                    </form>
                </div>'
            )

            // --- STANDAR FILAMENT ---
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([ Pages\Dashboard::class ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\DashboardStatsWidget::class,
                \App\Filament\Widgets\IncidentTrendChart::class,
                \App\Filament\Widgets\RecentIncidentsWidget::class,
                \App\Filament\Widgets\LiveRadarWidget::class,
            ])
            ->plugin(\Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()->selectable()->editable())
            // 👇 Middleware untuk mengecek "amarin123" 👇
            ->middleware([ EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class ])
            ->authMiddleware([ Authenticate::class ]);
    }
}
