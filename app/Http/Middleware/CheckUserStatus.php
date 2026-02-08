<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (
            auth()->check() &&
            auth()->user()->status === 'banned' &&
            auth()->user()->usertype !== 'admin'
        ) {
            auth()->logout();
    
            return redirect('/login')
                ->withErrors([
                    'email' => 'Your account has been banned.',
                ]);
        }
    
        return $next($request);
    }
    
}
