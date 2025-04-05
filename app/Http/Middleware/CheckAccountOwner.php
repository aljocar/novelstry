<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el nombre de usuario de la ruta
        $username = $request->route('username');

        // Verificar que el usuario autenticado es el dueño del perfil
        if (Auth::user()->username !== $username) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return $next($request);
    }
}
