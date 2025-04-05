<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar los middlewares con alias
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class, // Usuario Administrador
            'account.owner' => \App\Http\Middleware\CheckAccountOwner::class, // Usuario Dueño
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de excepciones
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login')); // Redirige al login
        });
    })->create();
