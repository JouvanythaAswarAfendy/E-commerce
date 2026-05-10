<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // On Vercel, ensure compiled views directory exists and is correctly configured
        if (getenv('VERCEL')) {
            $viewCompiledPath = '/tmp/storage/framework/views';
            if (!is_dir($viewCompiledPath)) {
                mkdir($viewCompiledPath, 0755, true);
            }
            config(['view.compiled' => $viewCompiledPath]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production (Vercel serves over HTTPS)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        \Illuminate\Support\Facades\View::composer(
            'components.header',
            \App\View\Composers\NavigationComposer::class
        );
    }
}
