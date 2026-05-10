<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\ForceHttps::class);
        
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule): void {
        $schedule->call(function () {
            \App\Models\Order::query()
                ->where('status', '=', 'pending', 'and')
                ->where('created_at', '<=', now()->subHours(24), 'and')
                ->update(['status' => 'dibatalkan']);
        })->hourly();
    })->create();

// Set storage path for Vercel
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;
