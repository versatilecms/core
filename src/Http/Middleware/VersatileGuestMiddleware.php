<?php

namespace Versatile\Core\Http\Middleware;

use Closure;

class VersatileGuestMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }

        return redirect()->back();
    }
}
