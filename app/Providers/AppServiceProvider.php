<?php

namespace App\Providers;

use App\Providers\Auth\EloquentTokenProvider;
use App\Providers\Filament\AdminPanelProvider;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('filakit.admin_panel_enabled', false)) {
            $this->app->register(AdminPanelProvider::class);
        }
        if (config('filakit.favicon.enabled')) {
            FilamentView::registerRenderHook(PanelsRenderHook::HEAD_START, fn (): View => view('components.favicon'));
        }
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_START, fn (): View => view('components.js-md5'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureAuthToken();
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales([
                    'en',
                    'es',
                    'pt_BR',
                ])
                ->flags([
                    'en' => asset('assets/flags/us.svg'),
                    'es' => asset('assets/flags/es.svg'),
                    'pt_BR' => asset('assets/flags/br.svg'),
                ]);
        });
    }

    private function configureAuthToken(): void
    {
        Auth::provider('eloquent-token-custom', function ($app, array $config) {
            return new EloquentTokenProvider($config['model']);
        });
    }
}
