<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use MarketPlex\SaleTransaction as Sale;
use Carbon\Carbon;

class SaleTransaction extends Model
{
    //
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [ 'id', 'bill_id', 'client_name', 'created_at' ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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

    public function getBillAmount()
    {
        return $this->productbills->map(function ($bill, $key) {
            return $bill->product->mrp * $bill->quantity;
        })->sum() + $this->shippingbills->map(function ($bill, $key) {
            return $bill->amount * $bill->quantity;
        })->sum();
    }

    public function getBillAmountDecimalFormat()
    {
        return number_format($this->getBillAmount(), 2);
    }

    public function getTotalPaidAmount()
    {
        return $this->billpayments->sum('amount');
    }

    public function getTotalPaidAmountDecimalFormat()
    {
        return number_format($this->getTotalPaidAmount() , 2);
    }

    public function getCurrentDueAmount()
    {
        return $this->getBillAmount() - $this->getTotalPaidAmount();
    }

    public function getCurrentDueAmountDecimalFormat()
    {
        return number_format($this->getCurrentDueAmount(), 2);
    }

    public function getPreviousDueAmount()
    {
        return Sale::others()->sameClient()->get()
                             ->sum(function($sale) { return $sale->getCurrentDueAmount(); });
    }

    public function getPreviousDueAmountDecimalFormat()
    {
        return number_format($this->getPreviousDueAmount(), 2);
    }

    public function getTotalDueAmount()
    {
        return Sale::sameClient()->get()
                                 ->sum(function($sale) { return $sale->getCurrentDueAmount(); });
    }

    public function getTotalDueAmountDecimalFormat()
    {
        return number_format($this->getTotalDueAmount(), 2);
    }

    public function scopeIncomesToday($query)
    {
        return $this->scopeIncomesOn($query, Carbon::today()->toDateString());
    }

    public function scopeIncomesOn($query, $when)
    {
        return $query->whereDate('created_at', '=', $when);
    }

    public function scopeIncomesBetween($query, array $during)
    {
        return $query->whereBetween('created_at', $during);
    }

    public function scopeOthers($query)
    {
        return $query->where('id', '!=', $this->id);
    }

    public function scopeSameClient($query)
    {
        return $query->whereClientName($this->client_name);
    }
}
