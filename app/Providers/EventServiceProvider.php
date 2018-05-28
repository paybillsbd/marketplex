<?php

namespace MarketPlex\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'MarketPlex\Events\ClientAction' => [
            'MarketPlex\Listeners\ClientActionListener',
        ],
        'MarketPlex\Events\ProductCheckedIn' => [],
        'MarketPlex\Events\ProductCheckedOut' => [],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'MarketPlex\Listeners\ProductEventSubscriber',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
