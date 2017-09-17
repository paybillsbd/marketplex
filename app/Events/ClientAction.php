<?php

namespace MarketPlex\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ClientAction implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The name of the queue on which to place the event.
     *
     * @var string
     */
    public $broadcastQueue = 'client-action';

    /**
     * Information about the request created by user action (Must be public, otherwise laravel can't serialize).
     *
     * @var string
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $request)
    {
        //
        $this->request = $request;
        // dd($request);
        // Log::info(collect(config('broadcasting.connections'))->toJson());
        // Log::info(config('broadcasting.connections'));
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->request;
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        // Log::info($this->broadcastQueue . '.' . strtolower( config('app.name') . '.' . config('app.vendor') ));
        return new Channel($this->broadcastQueue . '.' . strtolower( config('app.name') . '.' . config('app.vendor') ));
    }
}
