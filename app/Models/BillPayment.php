<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'method', 'amount' ];

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
