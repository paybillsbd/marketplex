<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DateScope;

class BillPayment extends Model
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

    public function scopeCashReceived($query)
    {
        return $query->whereIn('method', [
            'by_cash_hand2hand',
            'by_cash_cheque_deposit',
            'by_cash_electronic_trans'
        ]);
    }

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
