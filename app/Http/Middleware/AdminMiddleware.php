<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // <--- ESTA ES LA LÍNEA QUE FALTA

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ahora sí reconocerá el comando Auth
        if (Auth::check() && Auth::user()->id_rol == 1) {
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes permisos de administrador.');
    }
}