<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PropietarioMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, $next) {
    if (Auth::check() && Auth::user()->id_rol == 3) {
        return $next($request);
    }
    return redirect('/')->with('error', 'No tienes permiso de Administrador.');
}
}
