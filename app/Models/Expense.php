<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DateScope;

class Expense extends Model
{
    use SoftDeletes;
    use DateScope;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = [ 'id', 'purpose', 'amount', 'created_at' ];

    public function sale()
    {
        return $this->belongsTo('MarketPlex\SaleTransaction');
    }

    public static function saveManyExpenses(array $expenseAmounts, $sale)
    {
        $expenses = collect([]);
        $ids = collect([]);
        foreach ($expenseAmounts as $value)
        {
            $e = Expense::find($value['expense_id']) ?: new Expense();
            $e->purpose = $value['expense_purpose'];
            $e->amount = $value['expense_amount'];
            $expenses->push($d);

            if ($value['expense_id'] != -1)
                $ids->push($value['expense_id']);
        }
        $saleExpenses = $sale->expenses();
        $removed = $saleExpenses->whereNotIn('id', $ids)->delete();
        return $saleExpenses->saveMany($expenses) || $removed;
    }
}
