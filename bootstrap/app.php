<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Providers\RouteServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'set.guard' => \App\Http\Middleware\SetDefaultGuard::class,
            'merchant' => \App\Http\Middleware\ResolveMerchant::class,
            'ensure.active.merchant'  => \App\Http\Middleware\EnsureActiveMerchant::class,
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withProviders([
        RouteServiceProvider::class,   // ★ これを追加
    ])
    ->create();
