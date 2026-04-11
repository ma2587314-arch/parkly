<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureVendor
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->isVendor()) {
            abort(403, 'Access denied.');
        }

        if ($request->user()->is_blocked) {
            abort(403, 'Your account has been suspended.');
        }

        return $next($request);
    }
}
