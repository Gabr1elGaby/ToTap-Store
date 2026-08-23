<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'subscribed' => \App\Http\Middleware\CheckSubscription::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/payment/webhook',
            'cv/preview/*',
            'topup/*/check-nickname',
            'topup/*/process',
            'topup/checkout/*/verify',
            'api/reviews',
            'admin/games/*/products/sync',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
