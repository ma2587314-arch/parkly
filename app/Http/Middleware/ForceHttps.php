<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('RAILWAY_ENVIRONMENT_NAME') || app()->environment('production')) {
            $request->server->set('HTTPS', 'on');
        }

        return $next($request);
    }
}
