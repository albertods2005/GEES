<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Permite acceder solo a usuarios administradores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->isAdmin()) {
            return redirect()->route('home')
                ->with('exito', 'Has iniciado sesion en la zona de usuario.');
        }

        return $next($request);
    }
}
