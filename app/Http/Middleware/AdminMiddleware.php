<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est connecté et s'il a le rôle 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Sinon, redirige vers le dashboard avec un message d'erreur
        return redirect('/dashboard')->with('error', 'Accès refusé. Vous n\'êtes pas administrateur.');
    }
}