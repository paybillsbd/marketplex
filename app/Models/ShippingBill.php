<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingBill extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'purpose', 'quantity', 'amount' ];
}
