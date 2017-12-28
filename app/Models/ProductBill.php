<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use MarketPlex\Product;

class ProductBill extends Model
{
    //

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
