<?php

namespace MarketPlex\Http\Middleware;

use Closure;

/*
 * A middleware that forces to use https
 */
class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && config('app.env') === 'production')
        {
        	$request->setTrustedProxies( [ $request->getClientIp() ] );
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request); 
    }
}