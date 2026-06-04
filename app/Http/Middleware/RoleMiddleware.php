<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            abort(401); // not logged in
        }

        if (Auth::user()->role !== $role) {
            abort(403); // wrong role
        }

        return $next($request);
    }
}