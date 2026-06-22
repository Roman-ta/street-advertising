<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckLegalSigned;
use App\Http\Middleware\CheckProfileComplete;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'             => CheckRole::class,
            'legal_signed'     => CheckLegalSigned::class,
            'profile_complete' => CheckProfileComplete::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Куда редиректить гостей (незалогиненных)
        $middleware->redirectGuestsTo('/login');

        // Куда редиректить залогиненных если они идут на /register или /login
        $middleware->redirectUsersTo(function () {
            if (!auth()->check()) return '/login';

            return match(auth()->user()->role) {
                'admin'   => '/admin/dashboard',
                'partner' => '/partner/dashboard',
                default   => '/client/dashboard',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
