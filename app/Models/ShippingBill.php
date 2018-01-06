<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class ShippingBill extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'purpose', 'quantity', 'amount' ];
}
