<?php

namespace MarketPlex\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            Log::info( '[' . config('app.vendor') . ']' . 'Dev user email found' . Auth::user()->email);
            if(Auth::user()->isAdmin() || Auth::user()->isDeveloper())
                return redirect('/home');
        }
        return $next($request);
    }
}
