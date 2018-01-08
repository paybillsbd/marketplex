<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillPayment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public static function getPaymentMethodText($method)
    {
    	switch($method)
    	{
    		case 'by_cash_hand2hand':
    			return 'By Cash (hand to hand)';
    		case 'by_cash_cheque_deposit':
    			return 'By Cash (cheque deposit)';
    		case 'by_cash_electronic_trans':
    			return 'By Cash (electronic transfer)';
    		case 'by_cheque_hand2hand':
    			return 'By Cheque (hand to hand)';
    	}
    }
}
