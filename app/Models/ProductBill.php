<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use MarketPlex\Product;

class ProductBill extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'product_id', 'quantity' ]; 

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
