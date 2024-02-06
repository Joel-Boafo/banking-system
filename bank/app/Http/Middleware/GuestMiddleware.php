<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // only allow guests to see the login and register pages
        if (auth()->check()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
