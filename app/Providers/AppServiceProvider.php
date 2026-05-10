<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (getenv('VERCEL')) {
            $this->app->bind('view', function ($app) {
                return $app->make(\Illuminate\View\Factory::class);
            });

            $viewCompiledPath = '/tmp/storage/framework/views';
            if (!is_dir($viewCompiledPath)) {
                mkdir($viewCompiledPath, 0755, true);
            }
            config(['view.compiled' => $viewCompiledPath]);
        }
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        if ($this->app->bound('view')) {
            \Illuminate\Support\Facades\View::composer(
                'components.header',
                \App\View\Composers\NavigationComposer::class
            );
        }
    }
}