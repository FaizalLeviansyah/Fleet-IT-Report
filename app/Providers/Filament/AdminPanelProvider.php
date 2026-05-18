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

            // --- KUSTOMISASI TEMA & UI DIMULAI DARI SINI ---
            ->colors([
                'primary' => Color::Blue, // Mengubah warna utama menjadi Biru Laut
            ])
            ->font('Poppins') // Menggunakan font modern Poppins
            ->brandName('Amarin IT System') // Nama Aplikasi
            ->brandLogo(asset('img/Logo_PT_ASM.jpg')) // Memanggil Logo Amarin
            ->brandLogoHeight('3rem') // Mengatur tinggi logo agar proporsional dan rapi

            // --- SUNTIKAN CSS UNTUK SIDEBAR BIRU TUA ---
            // --- SUNTIKAN CSS PREMIUM UNTUK UI MEWAH ---
            ->renderHook(
                'panels::head.end',
                fn (): string => '<style>
                    /* 1. Latar Belakang Sidebar Utama */
                    .fi-sidebar { background-color: #0F172A !important; }
                    .fi-sidebar-header { background-color: #0F172A !important; border-bottom: 1px solid #1E293B; padding-top: 1rem; padding-bottom: 1rem; }

                    /* 2. Memperbaiki Logo "Tembelan" menjadi Badge Mewah */
                    .fi-logo {
                        background-color: #ffffff;
                        padding: 10px 20px;
                        border-radius: 12px;
                        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.15);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 100%;
                    }
                    .fi-logo img { height: 35px !important; }

                    /* 3. Teks & Ikon Default (Agak redup agar elegan) */
                    .fi-sidebar-item-label, .fi-sidebar-item-icon {
                        color: #94A3B8 !important;
                        transition: all 0.3s ease;
                    }

                    /* 4. Efek Hover (Saat kursor diarahkan) */
                    .fi-sidebar-item-button:hover {
                        background-color: #1E293B !important;
                        border-radius: 8px;
                    }
                    .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: #F8FAFC !important;
                    }

                    /* 5. Efek Active (Halaman yang sedang dibuka) - Gradasi Premium */
                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background: linear-gradient(90deg, #1E3A8A 0%, #0F172A 100%) !important;
                        border-left: 4px solid #3B82F6 !important;
                        border-radius: 0 8px 8px 0 !important;
                    }
                    .fi-sidebar-item-active .fi-sidebar-item-label,
                    .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #ffffff !important;
                        font-weight: bold;
                    }
                </style>'
            )
            // -------------------------------------------
            // -------------------------------------------

            // 👇 DAFTARKAN TEMA CSS SIDEBAR BIRU TUA DI SINI 👇
            //->viteTheme('resources/css/filament/admin/theme.css')


            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // 🧹 SAYA HAPUS WIDGET BAWAAN FILAMENT DI SINI AGAR DASHBOARD BERSIH 🧹
                // (Widget WelcomeWidget dan Widget buatan kita lainnya akan otomatis terbaca
                // oleh perintah discoverWidgets di atas, jadi biarkan array ini kosong).
            ])
            // TAMBAHKAN KODE INI UNTUK MENGAKTIFKAN KALENDER:
            ->plugin(
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make()
                    ->selectable() // Agar kalender bisa diklik & di-drag
                    ->editable() // Agar jadwal bisa diedit
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
