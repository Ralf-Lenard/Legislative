<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleInertiaFlash
{
    /**
     * Handles flash messages manually passed via router.reload({ data: {...} })
     */
    public function handle(Request $request, Closure $next): Response
    {
        $flashKeys = ['success', 'error', 'warning', 'info'];

        // Check for flash messages in the request data
        foreach ($flashKeys as $key) {
            if ($request->has($key)) {
                // Use session()->now() for immediate consumption.
                $request->session()->now($key, $request->get($key));
            }
        }

        return $next($request);
    }
}