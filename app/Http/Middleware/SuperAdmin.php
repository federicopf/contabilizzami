<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Controlla se l'utente è autenticato e se è un superadmin
        if (Auth::check() && Auth::user()->superadmin) {
            return $next($request); // Passa la richiesta al prossimo middleware o controller
        }

        // Se non è superadmin, reindirizza o restituisci una risposta
        return redirect('/')->with('error', 'Accesso non autorizzato.');
    }
}
