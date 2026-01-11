<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !in_array($user->usertype, ['admin', 'super_admin'])) {
            return redirect('/')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}
