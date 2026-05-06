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
        // On Vercel, redirect storage to /tmp (the only writable directory)
        if (getenv('VERCEL')) {
            $storagePath = '/tmp/storage';

            $this->app->useStoragePath($storagePath);

            // Ensure compiled views directory exists
            $viewCompiledPath = $storagePath . '/framework/views';
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
