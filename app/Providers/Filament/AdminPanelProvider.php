<?php

namespace App\Providers\Filament;

use App\Filament\Resources\OrderResource\Widgets\StatsOverview;
use App\Filament\Resources\UserResource\Widgets\orderOverview;
use App\Filament\Resources\UserResource\Widgets\userOverview;
use App\Http\Middleware\ApplyTenantScopes;
use App\Models\Team;
use Filament\Forms\Components\Group;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->tenant(Team::class)
            ->id('admin')
            ->path('admin')
            ->login()
            // ->breadcrumbs(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('poppins')
            ->brandLogo(asset('img/MardBlue.svg'))
            ->brandLogoHeight('40px')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                               
                // StatsOverview::class,
                // orderOverview::class,                 
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->tenantMiddleware([
                ApplyTenantScopes::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ], isPersistent: true)
            ->plugins([
                FilamentEditProfilePlugin::make()
                ->slug('my-profile')
                ->setTitle('My Profile')
                ->setNavigationGroup('Settings')
                ->setSort(3)
                ->setNavigationLabel('My Profile')
                ->setIcon('heroicon-s-user')                
                ->shouldShowDeleteAccountForm(false)
                ->shouldShowBrowserSessionsForm()
                ->shouldShowAvatarForm(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])

            ->userMenuItems([
                // 'profile' => MenuItem::make()->label('Edit profile'),
                // 'logout' => MenuItem::make()->label('Log out'),
                
                MenuItem::make()
                    ->label('My Profile')
                    ->url('/admin/my-profile')
                    ->icon('heroicon-o-user'),
                // ...
            ])

            ->navigationItems([
                NavigationItem::make('Themes')
                    ->url(request()->url() . '/themes')
                    ->icon('heroicon-s-paint-brush')
                    ->isActiveWhen(fn () => request()->is('*themes'))
                    ->group('Settings')
                    ->sort(4),
                
            ]);
             
            
            
            
    }
    
}
