<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->spa()

            // --- WARNA & TEMA ---
            ->colors(['primary' => Color::Blue])
            ->font('Poppins')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->databaseNotifications()

            // --- MENU PROFIL KUSTOM (Memicu Modal Edit Profile) ---
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile Settings')
                    ->url('javascript:window.dispatchEvent(new Event(\'open-profile-modal\'))')
                    ->icon('heroicon-o-cog-8-tooth'),
            ])
            ->renderHook('panels::body.end', fn (): string => \Illuminate\Support\Facades\Blade::render('@livewire("edit-profile-modal")'))

            // 👇 HOOK BARU: LOADING SCREEN SPA AMARIN (Pakai Vanilla JS Dijamin Muncul!) 👇
            // 👇 HOOK BARU: LOADING SCREEN UNIVERSAL (Bisa SPA & Bisa Ctrl+R) 👇
            // 👇 HOOK BARU: LOADING SCREEN UNIVERSAL (INSTAN & PASTI DI TENGAH!) 👇
            ->renderHook(
                'panels::body.start',
                fn (): string => '
                <div x-data="{ show: true }"
                     x-init="
                        if (document.readyState === \'complete\') { setTimeout(() => show = false, 300); }
                        else { window.addEventListener(\'load\', () => setTimeout(() => show = false, 300)); }
                     "
                     @livewire:navigating.window="show = true"
                     @livewire:navigated.window="setTimeout(() => show = false, 300)"
                     @trigger-loader.window="show = true"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="position: fixed; inset: 0; z-index: 999999; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">

                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100vw; height: 100vh;">
                        <div class="loader-container">
                            <div class="spinner-ring"></div>
                            <div class="spinner-ring-inner"></div>
                            <div class="logo-text">AMARIN</div>
                        </div>
                        <div class="loading-text">MEMUAT SISTEM...</div>
                    </div>
                </div>

                <script>
                    // JAVASCRIPT BYPASS: Menangkap klik H-1 milidetik sebelum Livewire bereaksi!
                    document.addEventListener("click", function(e) {
                        let link = e.target.closest("a");
                        // Pastikan yang diklik adalah menu sungguhan
                        if(link && link.href && !link.href.includes("javascript:") && link.getAttribute("target") !== "_blank") {
                            window.dispatchEvent(new CustomEvent("trigger-loader"));
                        }
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

            // --- RENDER HOOKS (CSS SUNTIKAN) ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <style>
                    /* SIDEBAR */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding: 1.5rem; }
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #94A3B8 !important; transition: all 0.3s ease; }
                    .fi-sidebar-item-button:hover { background-color: #1E293B !important; border-radius: 8px; transform: translateX(6px); }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label, .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #F8FAFC !important; }
                    .fi-sidebar-item-active .fi-sidebar-item-button { background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important; border-left: 4px solid #06B6D4 !important; border-radius: 0 8px 8px 0 !important; box-shadow: inset 20px 0 30px -20px rgba(6, 182, 212, 0.2) !important; }
                    .fi-sidebar-item-active .fi-sidebar-item-label, .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: bold; }

                    /* SHADOW CARD HALUS */
                    .fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat { background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 16px !important; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05) !important; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important; }
                    .fi-section:hover, .fi-ta-ctn:hover, .fi-wi-stats-overview-stat:hover { transform: translateY(-3px); box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.15) !important; border-color: rgba(37, 99, 235, 0.3) !important; }
                    .dark .fi-section, .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat { background-color: #111827 !important; border-color: #374151 !important; box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3) !important; }

                    /* NAVBAR ATAS GLASSMORPHISM */
                    .fi-topbar { padding: 0.5rem 1.5rem !important; height: auto !important; background-color: rgba(255, 255, 255, 0.7) !important; backdrop-filter: blur(12px) !important; -webkit-backdrop-filter: blur(12px) !important; border-bottom: 1px solid rgba(229, 231, 235, 0.5) !important; position: sticky !important; top: 0; z-index: 40; transition: all 0.3s ease; }
                    .dark .fi-topbar { background-color: rgba(15, 23, 42, 0.7) !important; border-bottom: 1px solid rgba(30, 41, 59, 0.5) !important; }
                    .fi-topbar > nav { background: transparent !important; gap: 0.5rem !important; align-items: center !important; }

                    /* MERAMPINGKAN GLOBAL SEARCH ASLI FILAMENT */
                    .fi-global-search { max-width: 220px !important; width: 100% !important; margin-right: 10px !important; }
                    .fi-global-search-input { height: 30px !important; font-size: 11px !important; border-radius: 9999px !important; background-color: #ffffff !important; border: 1px solid #E5E7EB !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; padding-left: 32px !important; }
                    .dark .fi-global-search-input { background-color: #1F2937 !important; border-color: #374151 !important; color: #D1D5DB !important; }

                    /* SEMBUNYIKAN TEMA BAWAAN DI DALAM PROFIL & TULISAN DASHBOARD */
                    .fi-user-menu .fi-theme-switcher { display: none !important; }
                    ' . (request()->routeIs('filament.admin.pages.dashboard') ? 'header.fi-header { display: none !important; }' : '') . '

                    /* Tombol Bawaan Filament Diperkecil */
                    .fi-topbar .fi-icon-btn { background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 50% !important; width: 30px !important; height: 30px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; color: #6B7280 !important; transition: all 0.2s ease; }
                    .fi-topbar .fi-icon-btn:hover { transform: scale(1.1); color: #2563EB !important; }
                    .fi-user-menu > button { background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 9999px !important; padding: 3px 12px 3px 3px !important; box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important; height: 30px !important; transition: all 0.2s ease; }
                    .fi-user-menu > button:hover { transform: scale(1.05); border-color: #2563EB !important; }

                    /* Compact Mode Logic */
                    body.is-compact .fi-ta-cell { padding-top: 0.3rem !important; padding-bottom: 0.3rem !important; }
                    body.is-compact .fi-section { padding: 0.75rem !important; }
                    .fi-wi-stats-overview-stat { padding: 1rem !important; }
                    .fi-wi-stats-overview-stat .text-3xl { font-size: 1.5rem !important; line-height: 2rem !important; }
                </style>'
            )

            // 👇 HOOK BARU: MENGATUR POSISI SEMPURNA NAVBAR 👇
            ->renderHook('panels::sidebar.nav.start', fn (): string => view('filament.components.sidebar-search')->render())

            // TOMBOL CREATE TICKET SEKARANG DI KIRI POL (PASTI MUNCUL)
            ->renderHook('panels::topbar.start', fn (): string => view('filament.components.navbar-search')->render())

            // TOMBOL TEMA & SHUTDOWN DI KANAN
            ->renderHook('panels::global-search.after', fn (): string => view('filament.components.topbar-actions')->render())

            // 👇 HOOK BARU: INJEKSI NAMA & JABATAN DI DALAM DROPDOWN PROFIL 👇
            // 👇 HOOK BARU: INJEKSI FOTO & JABATAN DI DALAM DROPDOWN PROFIL 👇
            ->renderHook(
                'panels::user-menu.profile.before',
                function (): string {
                    $user = auth()->user();
                    $jabatan = $user->jabatan ?? 'IT Support Team';
                    $namaLengkap = $user->full_name ?? 'Administrator';

                    // Logic Cek Foto Profil
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

            // TOMBOL LOGOUT SIDEBAR BAWAH
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
            ->widgets([])
            ->plugin(\Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()->selectable()->editable())
            ->middleware([ EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class ])
            ->authMiddleware([ Authenticate::class ]);
    }
}
