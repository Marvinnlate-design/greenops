<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TrackAnnouncementVisit
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            Session::put('last_announcement_visit', now());
        }
        return $next($request);
    }
}