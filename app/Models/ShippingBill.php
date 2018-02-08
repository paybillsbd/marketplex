<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingBill extends Model
{
    use SoftDeletes;

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

    public static function saveManyBills(array $shippingBills, $sale)
    {
        $bills = collect([]);
        $ids = collect([]);
        foreach ($shippingBills as $value)
        {
            $s = ShippingBill::find($value['shipping_bill_id']) ?: new ShippingBill();
            $s->purpose = $value[ 'shipping_purpose' ];
            $s->quantity = $value[ 'bill_quantity' ];
            $s->amount = $value[ 'bill_amount' ];
            $bills->push($s);

            if ($value['shipping_bill_id'] != -1)
                $ids->push($value['shipping_bill_id']);
        }
        $shippings = $sale->shippingbills();
        $removed = $shippings->whereNotIn('id', $ids)->delete();
        return $shippings->saveMany($bills) || $removed;
    }
}
