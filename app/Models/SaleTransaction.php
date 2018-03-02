<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use MarketPlex\Scopes\AuthUserProductScope;
use MarketPlex\SaleTransaction as Sale;
use Carbon\Carbon;
use Log;

// Note: applied global scopes: AuthUserProductScope
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

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new AuthUserProductScope);
    }

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

    public function getInvoiceRecordsCount()
    {
        return $this->productbills->count()
            + $this->shippingbills->count()
            + $this->billpayments->count();
    }

    public function nextRowIndex()
    {
        return $this->productbills->count()
            + $this->shippingbills->count()
            + $this->billpayments->count()
            + $this->deposits->count()
            + $this->expenses->count();
    }

    public function saveTransaction(array $inputs)
    {
        if (empty($inputs))
            return false;
        $this->client_id = 1;
        if (array_has($inputs, 'bill_id'))
        {
            $this->bill_id = $inputs['bill_id'];
        }
        $this->client_name = $inputs['client'];
        return $this->save();
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
            return $bill->getTotalAmount();
        })->sum() + $this->shippingbills->map(function ($bill, $key) {
            return $bill->getTotalAmount();
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
        $queryB = clone $query;
        $queryT = clone $query;
        $qc = $searchInputs['client_name'] ? $query->where('client_name', 'like', '%' . $searchInputs['client_name'] . '%') : null;
        $qb = $searchInputs['billing_id'] ? $queryB->Where('bill_id', 'like', '%' . $searchInputs['billing_id'] . '%') : null;
        $from = Carbon::parse($searchInputs['from_date'])->format('Y-m-d');
        $to = Carbon::parse($searchInputs['to_date'])->format('Y-m-d');
        $qt = empty($searchInputs['client_name']) && empty($searchInputs['billing_id']) ? $queryT->WhereBetween('created_at', [ $from, $to ]) : null;

        if ($qc && $qb && $qt)
        {
            return $qc->union($qb)->union($qt);
        }
        else if ($qc && $qb)
        {
            return $qc->union($qb);
        }
        else if ($qb && $qt)
        {
            return $qb->union($qt);
        }
        else if ($qc && $qt)
        {
            return $qc->union($qt);
        }
        else if ($qc)
        {
            return $qc;
        }
        else if ($qb)
        {
            return $qb;
        }
        else if ($qt)
        {
            return $qt;
        }
    }    

    public static function generateBillId()
    {
        $faker = \Faker\Factory::create('en_GB');
        $faker->addProvider(new \Faker\Provider\Base($faker));
        return strtoupper($faker->unique()->bothify(Carbon::today()->format('Y-m-d') . '-##??##??##??##??##??##??##??'));
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
