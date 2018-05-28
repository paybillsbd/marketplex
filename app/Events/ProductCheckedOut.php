<?php

namespace MarketPlex\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use MarketPlex\ProductShipment;

class ProductCheckedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $shipment;

    /**
     * Create a new event instance.
     *
     * @param  ProductShipment  $shipment
     * @return void
     */
    public function __construct(ProductShipment $shipment)
    {
        //
        $this->shipment = $shipment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('product-checkedout');
    }
}
