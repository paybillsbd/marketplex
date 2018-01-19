<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DateScope;

class Deposit extends Model
{
    use SoftDeletes;
    use DateScope;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function sale()
    {
        return $this->belongsTo('MarketPlex\SaleTransaction');
    }

    public function scopeToBank($query)
    {
        return $query->where('method', 'bank');
    }

    public function scopeToVault($query)
    {
        return $query->where('method', 'vault');
    }
}
