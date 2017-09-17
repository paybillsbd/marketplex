<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Request;
use Illuminate\Broadcasting\BroadcastException;
use MarketPlex\Security\ProtocolKeeper;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('event:send', function () {

	try
	{
	    event(new MarketPlex\Events\ClientAction(Request::all()));
	}
	catch(BroadcastException $ex)
	{
		Log::info($ex->getMessage());
	}

    $this->comment(Inspiring::quote());
})->describe('throws an event');