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

            // 👇 1. MENGAKTIFKAN SPA (SINGLE PAGE APPLICATION) - ANTI RELOAD! 👇
            ->spa()

            // --- WARNA & TEMA ---
            ->colors(['primary' => Color::Blue])
            ->font('Poppins')
            ->brandLogo(fn () => view('filament.components.logo'))
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)
            ->databaseNotifications()

            // --- MENU PROFIL KUSTOM ---
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label('My Profile')
                    ->url(fn (): string => \App\Filament\Pages\Auth\EditProfile::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])

            // --- RENDER HOOKS (SUNTIKAN CSS ANIMASI SUPER SMOOTH) ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <style>
                    /* SIDEBAR MEWAH & ANIMASI ICON */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding: 1.5rem; }
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #94A3B8 !important; transition: all 0.3s ease; }
                    .fi-sidebar-item-button:hover { background-color: #1E293B !important; border-radius: 8px; transform: translateX(6px); }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label, .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #F8FAFC !important; }

                    /* Menu Aktif Gradient Cyan */
                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important;
                        border-left: 4px solid #06B6D4 !important; border-radius: 0 8px 8px 0 !important;
                        box-shadow: inset 20px 0 30px -20px rgba(6, 182, 212, 0.2) !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-label, .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: bold; }

                    /* 👇 2. ANIMASI CARD MELAYANG & COLORFUL GLOW (SMOOTH PHYSICS) 👇 */
                    .fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 16px !important;
                        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05) !important;
                        /* Durasi animasi dibuat 0.4s dengan kurva ease-out agar mulus */
                        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                    }
                    /* Saat Mouse Hover: Card membesar 2%, Naik 5px, dan Muncul Cahaya Biru */
                    .fi-section:hover, .fi-ta-ctn:hover, .fi-wi-stats-overview-stat:hover {
                        transform: translateY(-5px) scale(1.02);
                        box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.15), 0 0 20px -5px rgba(37, 99, 235, 0.1) !important;
                        border-color: rgba(37, 99, 235, 0.3) !important;
                        z-index: 10;
                    }

                    .dark .fi-section, .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat {
                        background-color: #111827 !important; border-color: #374151 !important; box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3) !important;
                    }
                    .dark .fi-section:hover, .dark .fi-ta-ctn:hover, .dark .fi-wi-stats-overview-stat:hover {
                        box-shadow: 0 20px 40px -10px rgba(96, 165, 250, 0.2) !important; border-color: rgba(96, 165, 250, 0.4) !important;
                    }

                    /* NAVBAR ATAS GLASSMORPHISM */
                    .fi-topbar {
                        padding: 0.5rem 1.5rem !important; height: auto !important;
                        background-color: rgba(255, 255, 255, 0.7) !important;
                        backdrop-filter: blur(12px) !important; -webkit-backdrop-filter: blur(12px) !important;
                        border-bottom: 1px solid rgba(229, 231, 235, 0.5) !important;
                        position: sticky !important; top: 0; z-index: 40;
                        transition: all 0.3s ease;
                    }
                    .dark .fi-topbar { background-color: rgba(15, 23, 42, 0.7) !important; border-bottom: 1px solid rgba(30, 41, 59, 0.5) !important; }
                    .fi-topbar > nav { background: transparent !important; gap: 0.5rem !important; align-items: center !important; }

                    /* Tombol Bawaan Filament Diperkecil & Efek Hover */
                    .fi-topbar .fi-icon-btn {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 50% !important;
                        width: 28px !important; height: 28px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; color: #6B7280 !important;
                        transition: all 0.3s ease;
                    }
                    .fi-topbar .fi-icon-btn:hover { transform: scale(1.1) rotate(5deg); color: #2563EB !important; }

                    .fi-user-menu > button {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 9999px !important;
                        padding: 3px 12px 3px 3px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; height: 28px !important;
                        transition: all 0.3s ease;
                    }
                    .fi-user-menu > button:hover { transform: scale(1.05); border-color: #2563EB !important; }

                    .fi-global-search { display: none !important; }

                    /* Compact Mode */
                    body.is-compact .fi-ta-cell { padding-top: 0.3rem !important; padding-bottom: 0.3rem !important; }
                    body.is-compact .fi-section { padding: 0.75rem !important; }
                    .fi-wi-stats-overview-stat { padding: 1rem !important; }
                    .fi-wi-stats-overview-stat .text-3xl { font-size: 1.5rem !important; line-height: 2rem !important; }

                    /* MENGHILANGKAN TULISAN "DASHBOARD" */
                    ' . (request()->routeIs('filament.admin.pages.dashboard') ? 'header.fi-header { display: none !important; }' : '') . '
                </style>'
            )

            // HOOKS KOMPONEN NAVBAR
            ->renderHook('panels::sidebar.nav.start', fn (): string => view('filament.components.sidebar-search')->render())
            ->renderHook('panels::global-search.before', fn (): string => view('filament.components.navbar-search')->render())
            ->renderHook('panels::user-menu.before', fn (): string => view('filament.components.topbar-actions')->render())

            // 👇 3. TOMBOL LOGOUT SIDEBAR BAWAH DENGAN SWEETALERT POPUP 👇
            ->renderHook(
                'panels::sidebar.footer',
                fn (): string => '<div style="padding: 1rem; margin-top: auto;">
                    <form method="POST" action="'.route('filament.admin.auth.logout').'" style="margin: 0;">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="button"
                            onclick="Swal.fire({ title: \'Shutdown System?\', text: \'Sesi Anda akan diakhiri.\', icon: \'warning\', showCancelButton: true, confirmButtonColor: \'#ef4444\', cancelButtonColor: \'#64748b\', confirmButtonText: \'Ya, Shutdown!\', background: \'rgba(255,255,255,0.95)\', backdrop: \'rgba(15,23,42,0.7)\' }).then((result) => { if (result.isConfirmed) { this.closest(\'form\').submit(); } })"
                            style="display: flex; align-items: center; justify-content: center; width: 100%; gap: 8px; padding: 8px 16px; font-size: 12px; font-weight: bold; color: white; background: linear-gradient(to right, #dc2626, #e11d48); border-radius: 8px; border: 1px solid #f87171; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.4);">

                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 1 1-12.73 0M12 2v10"></path>
                            </svg>
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
