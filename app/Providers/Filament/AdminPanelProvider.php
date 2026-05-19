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

            // --- 1. WARNA & TEMA (KEMBALI KE NAVY BLUE) ---
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

            // --- 4. SUNTIKAN CSS PRESISI (LAYOUT ALA HR APP) ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '<style>
                    /* SIDEBAR NAVY KEMBALI */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding: 1.5rem; }
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #94A3B8 !important; transition: all 0.3s ease; }
                    .fi-sidebar-item-button:hover { background-color: #1E293B !important; border-radius: 8px; }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label, .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #F8FAFC !important; }
                    .fi-sidebar-item-active .fi-sidebar-item-button { background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important; border-left: 4px solid #3B82F6 !important; border-radius: 0 8px 8px 0 !important; }
                    .fi-sidebar-item-active .fi-sidebar-item-label, .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: bold; }

                    /* SHADOW CARD TEGAS & ELEGAN */
                    .fi-section, .fi-ta-ctn, .fi-wi-stats-overview-stat {
                        background-color: #ffffff !important;
                        border: 1px solid #E5E7EB !important;
                        border-radius: 16px !important;
                        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
                    }
                    .dark .fi-section, .dark .fi-ta-ctn, .dark .fi-wi-stats-overview-stat {
                        background-color: #111827 !important; border-color: #374151 !important;
                        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.3) !important;
                    }

                    /* MERAPIKAN NAVBAR ATAS ALA HR APP */
                    .fi-topbar { padding: 0.75rem 1.5rem !important; height: auto !important; border-bottom: 1px solid #F3F4F6 !important; }
                    .dark .fi-topbar { border-bottom-color: #1F2937 !important; }
                    .fi-topbar nav { gap: 0.75rem !important; align-items: center !important; }
                    
                    /* Tombol Lonceng & Bulan (Bulat Presisi 42px) */
                    .fi-topbar .fi-icon-btn {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 50% !important;
                        width: 42px !important; height: 42px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; color: #4B5563 !important;
                    }
                    .dark .fi-topbar .fi-icon-btn { background-color: #1F2937 !important; border-color: #374151 !important; color: #9CA3AF !important; }
                    
                    /* Tombol User Menu (Kapsul Presisi) */
                    .fi-user-menu > button {
                        background-color: #ffffff !important; border: 1px solid #E5E7EB !important; border-radius: 9999px !important;
                        padding: 4px 16px 4px 4px !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important; height: 42px !important;
                    }
                    .dark .fi-user-menu > button { background-color: #1F2937 !important; border-color: #374151 !important; }
                    
                    /* Hilangkan Search Default */
                    .fi-global-search { display: none !important; }

                    /* Logika Compact Mode */
                    body.is-compact .fi-ta-cell { padding-top: 0.3rem !important; padding-bottom: 0.3rem !important; }
                    body.is-compact .fi-section { padding: 0.75rem !important; }
                </style>'
            )
            
            // --- 5. RENDER HOOKS KOMPONEN ---
            // Search Khusus Sidebar
            ->renderHook('panels::sidebar.nav.start', fn (): string => view('filament.components.sidebar-search')->render())
            
            // Universal Search & System Online (Di kiri kumpulan tombol)
            ->renderHook('panels::global-search.before', fn (): string => view('filament.components.navbar-search')->render())
            
            // Display Preferences (Di samping kiri User Menu)
            ->renderHook('panels::user-menu.before', fn (): string => view('filament.components.topbar-actions')->render())

            // --- 6. STANDAR FILAMENT ---
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