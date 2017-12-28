<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class SaleTransaction extends Model
{
    //

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
