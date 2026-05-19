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

            // --- 1. WARNA & TEMA ---
            ->colors(['primary' => Color::Blue])
            ->font('Poppins')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)

            // --- 2. FITUR NOTIFIKASI ---
            ->databaseNotifications()

            // --- 3. MENU PROFIL KUSTOM ---
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->url(fn (): string => \App\Filament\Pages\Auth\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])

            // --- 4. RENDER HOOKS (SUNTIKAN SWEETALERT, ANIMASI, & UI) ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <style>
                    /* SIDEBAR MEWAH */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding: 1.5rem; }
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #94A3B8 !important; transition: all 0.3s ease; }
                    .fi-sidebar-item-button:hover { background-color: #1E293B !important; border-radius: 8px; transform: translateX(4px); }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label, .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #F8FAFC !important; }

                    /* Menu Aktif Gradient Cyan */
                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important;
                        border-left: 4px solid #06B6D4 !important; border-radius: 0 8px 8px 0 !important;
                        box-shadow: inset 20px 0 30px -20px rgba(6, 182, 212, 0.2) !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-label, .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: bold; }

                    /* SHADOW CARD HALUS */
                    .fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 16px !important;
                        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05) !important; transition: all 0.3s ease;
                    }
                    .dark .fi-section, .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat {
                        background-color: #111827 !important; border-color: #374151 !important; box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3) !important;
                    }

                    /* NAVBAR ATAS GLASSMORPHISM */
                    .fi-topbar {
                        padding: 0.5rem 1.5rem !important; height: auto !important;
                        background-color: rgba(255, 255, 255, 0.7) !important;
                        backdrop-filter: blur(12px) !important; -webkit-backdrop-filter: blur(12px) !important;
                        border-bottom: 1px solid rgba(229, 231, 235, 0.5) !important;
                        position: sticky !important; top: 0; z-index: 40;
                    }
                    .dark .fi-topbar { background-color: rgba(15, 23, 42, 0.7) !important; border-bottom: 1px solid rgba(30, 41, 59, 0.5) !important; }
                    .fi-topbar > nav { background: transparent !important; gap: 0.5rem !important; align-items: center !important; }

                    /* Tombol Bawaan Filament Diperkecil */
                    .fi-topbar .fi-icon-btn {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 50% !important;
                        width: 28px !important; height: 28px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; color: #6B7280 !important;
                    }
                    .fi-user-menu > button {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 9999px !important;
                        padding: 3px 12px 3px 3px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; height: 28px !important;
                    }

                    .fi-global-search { display: none !important; }

                    /* Compact Mode */
                    body.is-compact .fi-ta-cell { padding-top: 0.3rem !important; padding-bottom: 0.3rem !important; }
                    body.is-compact .fi-section { padding: 0.75rem !important; }
                    .fi-wi-stats-overview-stat { padding: 1rem !important; }
                    .fi-wi-stats-overview-stat .text-3xl { font-size: 1.5rem !important; line-height: 2rem !important; }

                    /* 👇 MENGHILANGKAN TULISAN "DASHBOARD" KHUSUS DI HALAMAN DASHBOARD 👇 */
                    ' . (request()->routeIs('filament.admin.pages.dashboard') ? 'header.fi-header { display: none !important; }' : '') . '
                </style>'
            )

            // HOOKS KOMPONEN
            ->renderHook('panels::sidebar.nav.start', fn (): string => view('filament.components.sidebar-search')->render())
            ->renderHook('panels::global-search.before', fn (): string => view('filament.components.navbar-search')->render())
            ->renderHook('panels::user-menu.before', fn (): string => view('filament.components.topbar-actions')->render())

            // 👇 TOMBOL LOGOUT SIDEBAR BAWAH (POWER ICON + SWEETALERT) 👇
            ->renderHook(
                'panels::sidebar.footer',
                fn (): string => '<div class="p-4 mt-auto">
                    <form method="POST" action="'.route('filament.admin.auth.logout').'">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="button"
                            onclick="Swal.fire({ title: \'Shutdown System?\', text: \'Sesi Anda akan diakhiri.\', icon: \'warning\', showCancelButton: true, confirmButtonColor: \'#ef4444\', cancelButtonColor: \'#64748b\', confirmButtonText: \'Ya, Shutdown!\', background: \'rgba(255,255,255,0.9)\', backdrop: \'rgba(15,23,42,0.7)\' }).then((result) => { if (result.isConfirmed) { this.closest(\'form\').submit(); } })"
                            class="flex items-center justify-center w-full gap-2 px-4 py-2 text-xs font-bold text-white transition-all duration-300 bg-gradient-to-r from-red-600 to-rose-500 rounded-lg hover:from-red-500 hover:to-rose-400 hover:shadow-lg hover:shadow-red-500/40 hover:-translate-y-1 border border-red-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 1 12.728 0M12 3v9"></path></svg>
                            SHUTDOWN
                        </button>
                    </form>
                </div>'
            )

            // --- 5. STANDAR FILAMENT (AUTO-DISCOVER DIKEMBALIKAN) ---
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([ Pages\Dashboard::class ])

            // 👇 WIDGET KEMBALI OTOMATIS TERBACA SEMUA 👇
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])

            ->plugin(\Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()->selectable()->editable())
            ->middleware([ EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class, AuthenticateSession::class, ShareErrorsFromSession::class, VerifyCsrfToken::class, SubstituteBindings::class, DisableBladeIconComponents::class, DispatchServingFilamentEvent::class ])
            ->authMiddleware([ Authenticate::class ]);
    }
}
