<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DataIntegrityScope;
use MarketPlex\Traits\TextInputParser;

use Log;

class ShippingBill extends Model
{
    use SoftDeletes;
    use DataIntegrityScope;
    use TextInputParser;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'sale_transaction_id', 'purpose', 'quantity', 'amount' ];

    public function getTotalAmount()
    {
        return $this->amount * $this->quantity;
    }

    public static function saveManyBills(array $shippingBills, $sale)
    {
        $bills = collect([]);
        $ids = collect([]);
        foreach ($shippingBills as $value)
        {
            $s = $value['shipping_bill_id'] == -1 ?
                    new ShippingBill() : ShippingBill::find($value['shipping_bill_id']);
            $s->purpose = $value[ 'shipping_purpose' ];
            $s->quantity = $value[ 'bill_quantity' ];
            $s->amount = self::toFloat($value[ 'bill_amount' ]);
            $bills->push($s);

            if ($value['shipping_bill_id'] != -1)
                $ids->push($value['shipping_bill_id']);
        }
        $shippings = $sale->shippingbills();
        $removed = $shippings->RemoveCrossed($shippingBills, $ids->toArray());
        return $shippings->saveMany($bills) || $removed;
    }
}
