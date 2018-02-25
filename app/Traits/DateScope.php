<?php

namespace MarketPlex\Traits;

trait DateScope
{    
    public function scopeSalesOn($query, $when)
    {
        return $query->with([
            'sale' => function($query) use ($when) {
                            return $query->whereDate('created_at', '=', $when); }
        ])->whereDate('created_at', $when);
    }

    public function scopeSalesBetween($query, array $during)
    {
        return $query->with([
            'sale' => function($query) use ($during) {
                            return $query->whereBetween('created_at', $during); }
        ])->whereBetween('created_at', $during);
    }
}