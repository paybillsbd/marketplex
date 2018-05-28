<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use MarketPlex\Product;
use MarketPlex\Store;
use Auth;
use Log;

use MarketPlex\Events\ProductCheckedIn;
use MarketPlex\Events\ProductCheckedOut;
use MarketPlex\ProductShipment;

class ProductBill extends Model
{
    use SoftDeletes;
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
    protected $touches = ['sale'];

    public function sale()
    {
        return $this->belongsTo('MarketPlex\SaleTransaction');
    }

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

    public function getTotalAmount()
    {
        return ($this->product ? $this->product->mrp : 0.00) * $this->quantity;
    }

    public function isProductAdded($newQuantity)
    {
        return $this->quantity < $newQuantity;
    }

    public function isProductRemoved($newQuantity)
    {
        return $this->quantity > $newQuantity;
    }

    public function removeQuantityFromProduct($newQuantity)
    {
        if (!$this->product)
            throw new Exception('Product must be valid while removing quantity');
        $removed_quantity = $newQuantity - $this->quantity;
        $this->product->available_quantity -= $removed_quantity;

        $shipment = new ProductShipment;
        $shipment->title = $this->product->title;
        $shipment->supplier = $this->product->marketManufacturer();
        $shipment->status = 'SALES_ADDED';
        $shipment->direction = 'CHECKIN_OUT';
        $shipment->tag = 'sale:'.$this->sale->bill_id;
        $shipment->stored_unit_total = $removed_quantity;
        $shipment->product()->associate($this->product);
        event(new ProductCheckedOut($shipment));
    }

    public function addQuantityToProduct($newQuantity)
    {
        if (!$this->product)
            throw new Exception('Product must be valid while adding quantity');
        $added_quantity = $this->quantity - $newQuantity;
        $this->product->available_quantity += $added_quantity;

        $shipment = new ProductShipment;
        $shipment->title = $this->product->title;
        $shipment->supplier = $this->product->marketManufacturer();
        $shipment->status = 'SALES_REMOVED';
        $shipment->direction = 'CHECKIN_IN';
        $shipment->tag = 'sale:'.$this->sale->bill_id;
        $shipment->stored_unit_total = $added_quantity;
        $shipment->product()->associate($this->product);
        event(new ProductCheckedIn($shipment));
    }

    public function updateProductQuantity($newQuantity)
    {
        $product = $this->product;
        if ($product)
        {
            if ($this->isProductAdded($newQuantity))
            {
                $this->removeQuantityFromProduct($newQuantity);
            }
            if ($this->isProductRemoved($newQuantity))
            {
                $this->addQuantityToProduct($newQuantity);
            }
        }
        return !is_null($product);
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
            if ($p->updateProductQuantity($value['product_quantity']))
            {
                $products->push($p->product);
            }
            else
            {
                // For those product which are added to sales without product profile
                // User could insert product instantly in sales book which are not present
                // in product collection
                $instantProduct = new Product;
                $instantProduct->title = $p->product_title;
                $instantProduct->mrp = $p->product_price;
                $instantStore = new Store;
                $instantStore->name = $p->store_name;
                if ($instantProduct->save() && $instantStore->save())
                {
                    $instantProduct->store()->associate($instantStore);
                }
                $instantProduct->user()->associate(Auth::user());

                if ($p->isProductAdded($value['product_quantity']))
                {
                    $shipment = new ProductShipment;
                    $shipment->title = $instantProduct->title;
                    $shipment->status = 'SALES_ADDED';
                    $shipment->direction = 'CHECKIN_OUT';
                    $shipment->tag = 'sale:'.$p->sale->bill_id;
                    $shipment->stored_unit_total = $removed_quantity;
                    $shipment->product()->associate($instantProduct);
                    $p->product()->associate($instantProduct);
                    event(new ProductCheckedOut($shipment));
                }
                if ($p->isProductRemoved($value['product_quantity']))
                {
                    $shipment = new ProductShipment;
                    $shipment->title = $instantProduct->title;
                    $shipment->status = 'SALES_REMOVED';
                    $shipment->direction = 'CHECKIN_OUT';
                    $shipment->tag = 'sale:'.$p->sale->bill_id;
                    $shipment->stored_unit_total = $added_quantity;
                    $shipment->product()->associate($instantProduct);
                    $p->product()->associate($instantProduct);
                    event(new ProductCheckedIn($shipment));
                }
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
                $p = $billedProduct->product;
                if ($p)
                {
                    $p->available_quantity += $billedProduct->quantity;
                    $data = [ 'quantity' => $billedProduct->quantity, 'tag' => 'sale:'.$billedProduct->sale->bill_id ];
                    event(new ProductCheckedIn($product, $billedProduct->quantity));
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
