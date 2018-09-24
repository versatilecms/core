<?php

namespace Versatile\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Versatile\Core\Facades\Versatile;

class VersatileAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $user = auth()->user();
            if (isset($user->locale)) {
                app()->setLocale($user->locale);
            }

            if ($user->hasPermission('browse_admin')) {
                return $next($request);
            }

            return redirect('/');
        }

        $urlLogin = route('versatile.login');

        return redirect()->guest($urlLogin);
    }
}
