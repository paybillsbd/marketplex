<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use MarketPlex\SaleTransaction as Sale;

class SaleTransaction extends Model
{
    //
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

    public function getBillAmount()
    {
        $totalAmount = 0.0;
        foreach ($this->productbills as $key => $bill) {
            $totalAmount += $bill->product->mrp * $bill->quantity;
        }
        foreach ($this->shippingbills as $key => $bill) {
            $totalAmount += $bill->amount * $bill->quantity;
        }
        return $totalAmount;
    }

    public function getBillAmountDecimalFormat()
    {
        return number_format($this->getBillAmount(), 2);
    }

    public function getCurrentDueAmount()
    {
        $totalPaidAmount = 0.0;
        foreach ($this->billpayments as $key => $payment) {
            $totalPaidAmount += $payment->amount;
        }
        return $this->getBillAmount() - $totalPaidAmount;
    }

    public function getCurrentDueAmountDecimalFormat()
    {
        return number_format($this->getCurrentDueAmount(), 2);
    }

    public function getPreviousDueAmount()
    {
        $totalAmountDue = 0.0;
        foreach (Sale::whereClientName($this->client_name)->where('id', '!=', $this->id)->get() as $key => $sale) {
            $totalAmountDue += $sale->getCurrentDueAmount();
        }
        return $totalAmountDue;
    }

    public function getPreviousDueAmountDecimalFormat()
    {
        return number_format($this->getPreviousDueAmount(), 2);
    }

    public function getTotalDueAmount()
    {
        $totalAmountDue = 0.0;
        foreach (Sale::whereClientName($this->client_name)->get() as $key => $sale) {
            $totalAmountDue += $sale->getCurrentDueAmount();
        }
        return $totalAmountDue;
    }

    public function getTotalDueAmountDecimalFormat()
    {
        return number_format($this->getTotalDueAmount(), 2);
    }
}
