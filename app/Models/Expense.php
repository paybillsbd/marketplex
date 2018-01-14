<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

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

    public function scopeSale($query, $sale)
    {
        return $query->where('sale_transaction_id', $sale->id);
    }

    public function sale()
    {
        return $this->belongsTo('MarketPlex\SaleTransaction');
    }
}
