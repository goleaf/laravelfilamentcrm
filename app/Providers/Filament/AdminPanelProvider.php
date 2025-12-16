<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages as FilamentPage;
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
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Jetstream\Jetstream;
use App\Filament\App\Pages\EditProfile;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // #region agent log
        file_put_contents('/www/wwwroot/filamentsocialnetwork.prus.dev/.cursor/debug.log', json_encode(['sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'D', 'location' => 'AdminPanelProvider.php:27', 'message' => 'Panel registration started', 'data' => ['panelId' => 'admin']], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
        // #endregion
        
        $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login([AuthenticatedSessionController::class, 'create'])
            ->passwordReset()
            ->emailVerification()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors([
                'primary' => Color::Gray,
            ]);
        
        // #region agent log
        file_put_contents('/www/wwwroot/filamentsocialnetwork.prus.dev/.cursor/debug.log', json_encode(['sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'A', 'location' => 'AdminPanelProvider.php:43', 'message' => 'Before widget discovery', 'data' => ['widgetPath' => app_path('Filament/Admin/Widgets/Home'), 'exists' => is_dir(app_path('Filament/Admin/Widgets/Home'))]], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
        // #endregion
        
        // #region agent log
        $resourcesPath1 = app_path('Filament/Resources');
        $resourcesPath2 = app_path('Filament/Admin/Resources');
        file_put_contents('/www/wwwroot/filamentsocialnetwork.prus.dev/.cursor/debug.log', json_encode(['sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'B', 'location' => 'AdminPanelProvider.php:45', 'message' => 'Resource discovery paths', 'data' => ['path1' => $resourcesPath1, 'exists1' => is_dir($resourcesPath1), 'path2' => $resourcesPath2, 'exists2' => is_dir($resourcesPath2)]], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
        // #endregion
        
        $panel->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages');
            
        // Only discover widgets if directory exists
        $widgetsPath = app_path('Filament/Admin/Widgets/Home');
        if (is_dir($widgetsPath)) {
            $panel->discoverWidgets(in: $widgetsPath, for: 'App\\Filament\\Admin\\Widgets\\Home');
        }
        
        // #region agent log
        file_put_contents('/www/wwwroot/filamentsocialnetwork.prus.dev/.cursor/debug.log', json_encode(['sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'A', 'location' => 'AdminPanelProvider.php:56', 'message' => 'Widget discovery handled', 'data' => ['widgetDirExists' => is_dir($widgetsPath)]], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
        // #endregion
        
        $panel->pages([
                FilamentPage\Dashboard::class,
                EditProfile::class,
                // Pages\ApiTokenManagerPage::class,
            ])->widgets([
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        // if (Features::hasApiFeatures()) {
        //     $panel->userMenuItems([
        //         MenuItem::make()
        //             ->label('API Tokens')
        //             ->icon('heroicon-o-key')
        //             ->url(fn () => $this->shouldRegisterMenuItem()
        //                 ? url(Pages\ApiTokenManagerPage::getUrl())
        //                 : url($panel->getPath())),
        //     ]);
        // }

        // #region agent log
        file_put_contents('/www/wwwroot/filamentsocialnetwork.prus.dev/.cursor/debug.log', json_encode(['sessionId' => 'debug-session', 'runId' => 'run1', 'hypothesisId' => 'D', 'location' => 'AdminPanelProvider.php:103', 'message' => 'Panel registration completed', 'data' => ['panelId' => 'admin']], JSON_UNESCAPED_SLASHES) . "\n", FILE_APPEND);
        // #endregion
        
        return $panel;
    }

    public function boot()
    {
        /**
         * Disable Fortify routes.
         */
        Fortify::$registersRoutes = false;

        /**
         * Disable Jetstream routes.
         */
        Jetstream::$registersRoutes = false;
    }

    public function shouldRegisterMenuItem(): bool
    {
        return true; //auth()->user()?->hasVerifiedEmail() && Filament::hasTenancy() && Filament::getTenant();
    }
}
