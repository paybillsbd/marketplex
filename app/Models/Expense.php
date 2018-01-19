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
}
