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

    public function nextRowIndex()
    {
        return $this->productbills->count()
            + $this->shippingbills->count()
            + $this->billpayments->count()
            + $this->deposits->count()
            + $this->expenses->count();
    }

    public static function expectedQueries(array $queries)
    {
        return is_array($queries) && count($queries) == 4;
    }

    // If any query is found to search - returns false
    // otherwise returns true
    public static function nothingSearched(array $queries)
    {        
        $nullCount = 0;
        foreach ($queries as $value)
        {
            if (empty($value) && ++$nullCount == count($queries))
            {
                return true;
            }
        }
        return false;
    }

    public static function getBillAmountByBillId($bill_id)
    {
        $sale = Sale::whereBillId($bill_id)->first();
        return !$sale ? 0.00 : $sale->getBillAmount();
    }

    public static function getCurrentDueAmountByBillId($bill_id)
    {
        $sale = Sale::whereBillId($bill_id)->first();
        return !$sale ? 0.00 : $sale->getCurrentDueAmount();
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

    public function scopeSearch($query, array $searchInputs)
    {
        $from = Carbon::parse($searchInputs['from_date'])->format('Y-m-d');
        $to = Carbon::parse($searchInputs['to_date'])->format('Y-m-d');
        $sales = $query->where('client_name', 'like', '%' . $searchInputs['client_name'] . '%');
        $salesByBillID = $query->where('bill_id', 'like', '%' . $searchInputs['billing_id'] . '%');
        $salesByDate = $query->whereBetween('created_at', [ $from, $to ]);

        $sales = empty($searchInputs['client_name']) ? $salesByBillID->union($sales) : $salesByBillID;
        $sales = empty($searchInputs['billing_id']) ? $salesByDate->union($sales) : $salesByDate;
        return $sales->orderBy('created_at', 'desc');
    }

    public static function messages()
    {
        return [
            'empty_table' => [
                'sale_product' => 'Added products for sale will show up here ...',
                'product_shipping' => 'Added shipping cost notes will show up here ...',
                'bill_payment' => 'Customers paid bill will show up here ...',
                'deposit' => 'Added deposits will show up here ...',
                'expense' => 'Added expenses will show up here ...',
            ],
            'help' => [
                'save_sale' => 'Save your sold products entry records.',
                'add_product' => 'Add your ordered products from above selected store and product list.',
                'add_shipping' => 'Add your shipping costs.',
                'add_paid_bill' => 'Add your customer\'s paid bills.',
                'add_deposit' => 'Add your deposited amounts.',
                'add_expense' => 'Add your expenses from this incomes.',
            ]
        ];
    }
}
