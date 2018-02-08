<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MarketPlex\Product;
use Auth;

class ProductBill extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @param array $Inputs
     * @param MarketPlex\SaleTransaction $sale
     */
    public function saveBill(array $inputs, $sale)
    {
        if (empty($inputs))
            return false;
        $p->product_id = $inputs['product_id'];
        $p->quantity = $inputs['product_quantity'];
        return $sale->productbills()->save($p);
    }

    public static function saveManyBills(array $productBills, $sale)
    {
        $bills = collect([]);
        $ids = collect([]);
        $products = collect([]);
        foreach ($productBills as $value)
        {
            $p = ProductBill::find($value['product_bill_id']) ?: new ProductBill();
            
            $p->product_id = $value['product_id'];

            $added = $p->quantity < $value['product_quantity'];
            $removed = $p->quantity > $value['product_quantity'];
            $product = Product::find($p->product_id);
            if ($product)
            {
                if ($added)
                {
                    $product->available_quantity -= $p->quantity;
                }
                if ($removed)
                {
                    $product->available_quantity += $p->quantity;   
                }
                $products->push($product);
            }

            $p->quantity = $value['product_quantity'];
            $bills->push($p);

            if ($value['product_bill_id'] != -1)
                $ids->push($value['product_bill_id']);
        }
        $billedProducts = $sale->productbills();
        $removingProducts = $billedProducts->whereNotIn('id', $ids);
        $removed = $removingProducts->delete();
        if ($removed)
        {
            Auth::user()->products()->saveMany($removingProducts->map(function ($billedProduct, $key) {
                $p = Product::find($billedProduct->product_id);
                if ($p)
                {
                    $product->available_quantity += $billedProduct->quantity;
                }
                return $p;
            })->all() );
        }
        $saved = $billedProducts->saveMany($bills);
        if ($saved)
        {
            Auth::user()->products()->saveMany($products);
        }
        return $saved || $removed;
    }
}
