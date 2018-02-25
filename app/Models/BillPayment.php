<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DateScope;
use MarketPlex\Traits\DataIntegrityScope;
use MarketPlex\Traits\TextInputParser;

class BillPayment extends Model
{
    use SoftDeletes;
    use DateScope;
    use DataIntegrityScope;
    use TextInputParser;

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

    public static function saveManyPayments(array $billPayments, $sale)
    {
        $payments = collect([]);
        $ids = collect([]);
        foreach ($billPayments as $value)
        {
            $p = BillPayment::find($value['paid_bill_id']) ?: new BillPayment();
            $p->method = $value[ 'trans_option' ];
            $p->amount = self::toFloat($value[ 'paid_amount' ]);
            $payments->push($p);

            if ($value['paid_bill_id'] != -1)
                $ids->push($value['paid_bill_id']);
        }
        $paidAmounts = $sale->billpayments();
        $removed = $paidAmounts->RemoveCrossed($billPayments, $ids->toArray());
        return $paidAmounts->saveMany($payments) || $removed;
    }
}
