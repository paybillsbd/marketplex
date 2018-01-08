<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    //
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [ 'deleted_at', 'updated_at', 'client_id' ];
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [ 'id', 'bill_id', 'client_name', 'created_at' ];

    public function productbills()
    {
        return $this->hasMany('MarketPlex\ProductBill');
    }

    public function shippingbills()
    {
        return $this->hasMany('MarketPlex\ShippingBill');
    }

    public function billpayments()
    {
        return $this->hasMany('MarketPlex\BillPayment');
    }

    public function deposits()
    {
        return $this->hasMany('MarketPlex\Deposit');
    }

    public function expenses()
    {
        return $this->hasMany('MarketPlex\Expense');
    }
}
