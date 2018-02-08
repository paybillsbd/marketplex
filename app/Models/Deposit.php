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

    public static function saveManyDeposits(array $depositAmounts, $sale)
    {
        $deposits = collect([]);
        $ids = collect([]);
        foreach ($depositAmounts as $value)
        {
            $d = Deposit::find($value['bank_deposit_id']) ?: new Deposit();
            $d->method = $value['deposit_method'];
            $d->bank_title = $value[ 'bank_title' ];
            $d->bank_account_no = $value[ 'bank_ac_no' ];
            $d->bank_branch = $value[ 'bank_branch' ];
            $d->amount = $value[ 'deposit_amount' ];
            $deposits->push($d);

            if ($value['bank_deposit_id'] != -1)
                $ids->push($value['bank_deposit_id']);
        }
        $saleDeposits = $sale->deposits();
        $removed = $saleDeposits->whereNotIn('id', $ids)->delete();
        return $saleDeposits->saveMany($deposits) || $removed;
    }
}
