<?php

namespace MarketPlex;

use Log;
use Illuminate\Database\Eloquent\Model;

class ProductShipment extends Model
{
    //	 
    public function product()
    {
        return $this->belongsTo('MarketPlex\Product');
    }

    public static function saveProductShipment()
    {
    	
    }

    public function getUnit()
    {
    	return ($this->store_unit_type == 'WEIGHT' ? '' : '  KG');
    }

    public function getItemCountFormat()
    {
    	return number_format($this->stored_unit_total, 2);
    }

    public function getItemTotalPriceFormat()
    {
    	if ($this->store_unit <= 0 || $this->stored_unit_total < $this->store_unit)
    	{
    		Log::alert(config('app.vendor').'[store unit: '.$this->store_unit.'][store unit total: '.$this->stored_unit_total.']');
    		return number_format($this->price * ($this->stored_unit_total / 1.00), 2);
    	}
    	return number_format($this->price * ($this->stored_unit_total / $this->store_unit), 2);
    }
}
