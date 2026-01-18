<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware

{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check if user role is Staff
        if (Auth::user()->UserRole !== 'Staff') {
            return redirect()->route('dashboard')->with('error', 'Access denied. Staff only.');
        }

        return $next($request);
    }
}
