<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MarketPlex\Product;
use Auth;
use Log;

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
            $p = $value['product_bill_id'] == -1 ?
                    new ProductBill() : ProductBill::find($value['product_bill_id']);
            
            $p->product_id = $value['product_id'];
                   
            Log::info('quantity/' . ($p->quantity));
            Log::info('product_bill_id/' . ($value['product_bill_id']));

            $added = $p->quantity < $value['product_quantity'];
            $removed = $p->quantity > $value['product_quantity'];
            $product = Product::find($p->product_id);
            if ($product)
            {
                Log::info($p->product_id . '/' . ($value['product_quantity'] - $p->quantity));
                if ($added)
                {
                    $product->available_quantity -= ($value['product_quantity'] - $p->quantity);
                }
                if ($removed)
                {
                    $product->available_quantity += ($p->quantity - $value['product_quantity']);   
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
        $removingProductCollection = $removingProducts->get();
        $removed = $removingProducts->delete();
        if ($removed)
        {
            $removed = Auth::user()->products()
                                   ->saveMany($removingProductCollection
                                                ->map(function ($billedProduct, $key)
            {
                $p = Product::find($billedProduct->product_id);
                if ($p)
                {
                    $p->available_quantity += $billedProduct->quantity;
                }
                return $p;
            })->all() );
        }
        $saved = $billedProducts->saveMany($bills);
        if ($saved)
        {
            $saved = Auth::user()->products()->saveMany($products);
        }
        return $saved || $removed;
    }
}
