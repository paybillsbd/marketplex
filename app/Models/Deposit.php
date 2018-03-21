<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Traits\DateScope;
use MarketPlex\Traits\DataIntegrityScope;
use MarketPlex\Traits\TextInputParser;
use Log;

class Deposit extends Model
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
    
    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['sale_transaction'];

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
            $d->bank_branch = $value[ 'bank_branch' ];

            $bank_account = Bank::find($value[ 'bank_ac_no' ]);
            $d->bank_account_no = $bank_account ? $bank_account->account_no : '';

            $d->amount = self::toFloat($value[ 'deposit_amount' ]);
            $deposits->push($d);

            if ($value['bank_deposit_id'] != -1)
                $ids->push($value['bank_deposit_id']);
        }
        $saleDeposits = $sale->deposits();
        $removed = $saleDeposits->RemoveCrossed($depositAmounts, $ids->toArray());
        return $saleDeposits->saveMany($deposits) || $removed;
    }
}
