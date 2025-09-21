<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    /**
     * Maneja la petición entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Asegurate de tener la lógica correcta para identificar si un usuario es administrador.
        // Por ejemplo, si tu modelo User tiene un campo 'is_admin':
        if (!$request->user() || !$request->user()->is_admin) {
            abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}
