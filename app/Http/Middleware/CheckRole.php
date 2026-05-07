<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        // 2. Check if user has the required role
        if (Auth::user()->role !== 'admin'){
            abort(403, 'Unauthorized Action');
        }
        return $next($request);
    }
}
