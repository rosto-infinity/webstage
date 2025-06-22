<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if(!Auth::check() || !in_array(Auth::user()->role, $roles))
        {
           return Inertia::render('Welcome');
        }
        return $next($request);
    }
}
