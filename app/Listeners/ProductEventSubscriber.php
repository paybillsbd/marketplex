<?php

namespace MarketPlex\Listeners;

class ProductEventSubscriber
{
    /**
     * Handle product checkin events.
     */
    public function onProductCheckedIn($event)
    {
        if ($event->shipment->save())
        {
            $product = $event->shipment->product;
            $marketProduct = new MarketProduct;
            $marketProduct->title = $product->title;
            $marketProduct->manufacturer_name ?? $event->shipment->supplier;
            $marketProduct->category()->associate(null);
            $marketProduct->product()->associate($product);
            $marketProduct->save();
        }
    }

    /**
     * Handle product checkout events.
     */
    public function onProductCheckedOut($event)
    {
        if ($event->shipment->save())
        {
            $product = $event->shipment->product;
            $marketProduct = new MarketProduct;
            $marketProduct->title = $product->title;
            $marketProduct->manufacturer_name ?? $event->shipment->supplier;
            $marketProduct->category()->associate(null);
            $marketProduct->product()->associate($product);
            $marketProduct->save();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'MarketPlex\Events\ProductCheckedIn',
            'MarketPlex\Listeners\ProductEventSubscriber@onProductCheckedIn'
        );

        $events->listen(
            'MarketPlex\Events\ProductCheckedOut',
            'MarketPlex\Listeners\ProductEventSubscriber@onProductCheckedOut'
        );
    }

}