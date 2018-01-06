<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'purpose', 'amount' ];
}
