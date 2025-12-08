<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if user is not logged in or not an admin
        if (!$user || strtolower($user->usertype) !== 'admin') {
            // Redirect to home or anywhere else
            return redirect('/')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}
