<?php

namespace MarketPlex\Listeners;

use MarketPlex\Events\ClientAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClientActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ClientAction  $event
     * @return void
     */
    public function handle(ClientAction $event)
    {
        //
    }
}
