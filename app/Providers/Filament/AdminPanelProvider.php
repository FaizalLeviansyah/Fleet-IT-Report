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

            // --- KUSTOMISASI TEMA & UI ---
            ->colors([
                'primary' => Color::Blue, 
            ])
            ->font('Poppins') 
            
            // 👇 MEMANGGIL LOGO KUSTOM (GLOW EFFECT & TEKS ITMS STACK) 👇
            ->brandLogo(fn () => view('filament.components.logo'))
            
            // 👇 MENGAKTIFKAN MENU UBAH PASSWORD (PROFILE) 👇
            ->profile(\App\Filament\Pages\Auth\EditProfile::class)

            // --- SUNTIKAN CSS PREMIUM UNTUK UI MEWAH ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '<style>
                    /* Latar Belakang Sidebar Utama */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding-top: 1.5rem; padding-bottom: 1.5rem; }
                    
                    /* Teks & Ikon Default */
                    .fi-sidebar-item-label, .fi-sidebar-item-icon { color: #94A3B8 !important; transition: all 0.3s ease; }
                    
                    /* Efek Hover (Saat kursor diarahkan) */
                    .fi-sidebar-item-button:hover { background-color: #1E293B !important; border-radius: 8px; }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon { color: #F8FAFC !important; }
                    
                    /* Efek Active (Halaman yang sedang dibuka) */
                    .fi-sidebar-item-active .fi-sidebar-item-button { 
                        background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important; 
                        border-left: 4px solid #60A5FA !important;
                        border-radius: 0 8px 8px 0 !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-label, 
                    .fi-sidebar-item-active .fi-sidebar-item-icon { color: #ffffff !important; font-weight: bold; }
                </style>'
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Dikosongkan agar Dashboard bersih dari widget bawaan
            ])
            ->plugin(
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()
                    ->selectable() 
                    ->editable() 
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}