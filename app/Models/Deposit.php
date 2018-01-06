<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'method', 'bank_title', 'bank_account_no', 'bank_branch', 'amount' ];
}
