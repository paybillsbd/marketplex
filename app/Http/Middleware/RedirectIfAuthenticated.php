<?php

namespace MarketPlex\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use MarketPlex\Mailers\ActionMailer;

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

            $mailer = new ActionMailer();
            $mailer->report($request);
            
            $authUser = Auth::user();
            if($authUser->isAdmin() || $authUser->isDeveloper() || $authUser->isGuest())
                return redirect('/home');
        }
        return $next($request);
    }
}
