<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketProduct extends Model
{
    use SoftDeletes;
    //
    protected $table = 'market_products';
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ 'category_id', 'status' ];

    const DECIMAL_DELIMITERS = ',';
    /**
     * Set the market product's price removing decimal delimiters. [e.g. 80,000 -> 80000 ]
     *
     * @param  string  $value
     * @return string
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(self::DECIMAL_DELIMITERS, '', $value);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    /**
     * Get all of the Market Product's specrules.
     */
    public function specrules()
    {
        return $this->morphMany(SpecRule::class, 'specificable');
    }

    /**
     * Get all of the Market products' product medias.
     */
    public function medias()
    {
        return $this->morphMany(ProductMedia::class, 'mediable');
    }

    public function thumbnail()
    {
        return $this->product ? $this->product->thumbnail() : ProductMedia::defaultThumbnailImage();
    }

    public function banner()
    {
        return $this->product ? $this->product->banner() : ProductMedia::defaultThumbnailImage();
    }

    public function scopeUserProducts($query, $user)
    {
        return $query->with([ 'product' => function($query) use ($user) { return $query->where('user_id', $user->id); } ]);
    }

    public function scopeCategorized($query, $category)
    {
        return $query->where('category_id', $category->id);
    }

    public function mrp()
    {
        return $this->product ? $this->product->mrp : $this->price;
    }
}
