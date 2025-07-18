<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckBannedUser;
use App\Http\Middleware\SanctumApiAuth;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/github/deploy'
        ]);
        $middleware->validateSignatures(except: [
            '/github/deploy',
            'github/deploy',
            'github/deploy/*',
        ]);
        $middleware->alias([
            'check.banned' => CheckBannedUser::class,
            'auth.sanctum.api' => SanctumApiAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
