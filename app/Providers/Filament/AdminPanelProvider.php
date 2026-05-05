<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\KegiatanHariIniWidget;
use App\Filament\Widgets\PanduanSistemWidget;
use App\Filament\Widgets\RekapKehadiranBulanIniWidget;
use App\Filament\Widgets\RekomendasiRemisiWidget;
use App\Filament\Widgets\WargaBinaanAktifWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
// ← Hapus import AccountWidget & FilamentInfoWidget
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
            ->id('/')
            ->path('/')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->favicon(asset('iqbal.png'))
            ->brandName('SI Rapor BINPRI')
            ->homeUrl('/')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                PanduanSistemWidget::class,
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                KegiatanHariIniWidget::class,
                RekapKehadiranBulanIniWidget::class,
                RekomendasiRemisiWidget::class,
                WargaBinaanAktifWidget::class,
            ])
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