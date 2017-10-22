<?php

namespace MarketPlex;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Auth;

use MarketPlex\Helpers\ImageManager;

class Product extends Model
{
    // use SoftDeletes;

    protected $table = 'products';

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
    protected $guarded = ['mrp', 'status'];

    const STATUS_FLOWS = [
        'ON_APPROVAL', 'UPLOAD_FAILED', 'APPROVED', 'REJECTED', 'OUT_OF_STOCK', 'AVAILABLE', 'NOT_AVAILABLE', 'ON_SHIPPING', 'REMOVED', 'COMING_SOON', 'SOLD', 'ORDERED'
    ];

    const VIEW_TYPES = [ 
        'group' => [
            'dropdown' => 'Dropdown',
            // 'checkboxes' => 'Checkboxes',
            'options' => 'Radio Controllers',
            'spinner' => 'Spinners'
        ],
        'single' => [
            // 'selectbox' => 'Selectbox',
            'label' => 'Label',
            'input' => 'Input Box'
        ]
    ];

    const EXISTANCE_TYPE = [ 'PHYSICAL' => 'Physical Product', 'DOWNLOADABLE' => 'Downloadable Product' ];
    
    protected $casts = [
        'special_specs' => 'json'
    ];

    const MAX_AVAILABLE_QUANTITY = 15;
    const MIN_AVAILABLE_QUANTITY = 1;

    const IMAGE_DISPLAY_TYPES = [
        'thumbnail' => 0,
        'banner' => 1,
        'front' => 2,
        'back' => 3,
    ];
	 
    public function user()
    {
        return $this->belongsTo('MarketPlex\User');
    }

    public function store()
    {
        return $this->belongsTo('MarketPlex\Store');
    }

    /**
     * Get all of the products' product medias.
     */
    public function medias()
    {
        return $this->morphMany(ProductMedia::class, 'mediable');
    }

    public function delete()
    {
        foreach($this->medias as $media)
        {
            $media->delete(); // call the ProductMedia delete()
        }
        
        return parent::delete();
    }

    public function saveMedias(array $data)
    {
        $success = true;
        if(collect($data)->has('uploaded_files') && !collect($data['uploaded_files'])->isEmpty())
        {
            $images = $this->images();
            foreach($data['uploaded_files'] as $file)
            {
                $isVideo = ProductMedia::isMedia($file->getMimeType(), 'VIDEO');
                $productMedia = new ProductMedia();
                $productMedia->is_public = $data['is_public'];
                $productMedia->url = $file->getRealPath();
                $productMedia->media_type = $isVideo ? 'VIDEO' : 'IMAGE';
                $productMedia->title = $file->getBasename();

                // remove old image of same media position if exists
                $image = $images->where('_image_position', $file->_media_position)->first();
                if($image)
                    $image->delete();

                $success = $this->medias()->save($productMedia);

                $imagesExisting = $this->medias->where('is_embed', false)->where('media_type', 'IMAGE');
                if($imagesExisting->count() > 4)
                {
                    $imagesExisting->first()->delete();
                }
            }
        }
        // $hasEmbed = $data['has_embed_video'];
        if(collect($data)->has('embed_url') && $data['embed_url'])
        {   
            $productMedia = $this->hasEmbedVideo() ? $this->videoEmbedUrl()['media'] : new ProductMedia();
            $productMedia->is_embed = true;
            $productMedia->is_public = $data['is_public'];
            $productMedia->url = $data['embed_url'];
            $productMedia->media_type = 'VIDEO';
            $productMedia->title = ProductMedia::uuid();

            $success = $this->medias()->save($productMedia);
        }
        return $success;
    }

    public function hasEmbedVideo()
    {
        return !$this->videoEmbedUrl()['is_default'];
    }

    public function videoEmbedUrl()
    {
        $embedMedia = $this->medias->where('is_embed', true)->where('media_type', 'VIDEO')->first();

        if($embedMedia)
            return [ 'is_default' => false, 'url' => $embedMedia->url, 'media' => $embedMedia ];
        return [ 'is_default' => true, 'url' => '' ];
    }

    public function thumbnail()
    {
        return $this->previewImage(Product::IMAGE_DISPLAY_TYPES['thumbnail']);
    }

    public function banner()
    {
        return $this->previewImage(Product::IMAGE_DISPLAY_TYPES['banner']);
    }

    public function specialSpecs()
    {
        $specs = [];
        $id = 1;
        if (is_null($this->special_specs))
            return $specs;
        foreach (json_decode($this->special_specs) as $key => $value) {
            $specs[$key] = collect($value)->merge([ 'id' => $id++ ])->toArray();
        }
        return $specs;
    }

    public function images()
    {
        $images = $this->medias->where('is_embed', false)->where('media_type', 'IMAGE');
        /*Sk Asadur Rahman Script*/
        foreach($images as $image){
            $_location = explode('_', $image->title);
            if(count($_location) > 1){
                $image->_image_position = $_location[1]; // runtime added properties
            }
        }
        /*EOS*/
        // $firstTime = true;
        while($images->count() < ProductMedia::MAX_ALLOWED_IMAGE)// && $firstTime)
        {
            $image = new ProductMedia();
            $image->is_public = true;
            $image->url = $this->defaultImage();
            $image->media_type = 'IMAGE';
            $image->title = ProductMedia::uuid();
            $image->is_embed = true; // THIS BOOLEAN IS SET TO CHECK IF IT'S A DEFAULT IMAGE OR NOT
            $image->_image_position = $images->count() + 1; // default image index
            $images->push($image);
            // $firstTime = false;
        }
        return $images;
    }

    public function previewImage($index)
    {
        $image = $this->images()->where('_image_position', $index + 1)->first();
        if(!$image)
        {            
            $image = new ProductMedia();
            $image->url = $this->defaultImage();
            $image->is_embed = true;
        }
        // foreach($image as $image); // Added by Asad
        $routeName = ($index == Product::IMAGE_DISPLAY_TYPES['thumbnail']  ? 'user::products.image.thumbnail' : 'user::products.medias.image');
        return $image->is_embed ? $image->url : route( $routeName , [ 'file_name' => $image->title, 'api_token' => ImageManager::PUBLIC_TOKEN ]);
    }
    
    // getImageURL() added by Asad
    public function getImageURL($index)
    {
        $image = $this->images()->where('_image_position', $index + 1)->first();
        if(!$image)
        {            
            $image = new ProductMedia();
            $image->url = $this->defaultImage();
            $image->is_embed = true;
        }
        // foreach($image as $image);
        return $image->url != $this->defaultImage() ? [ 'title' => $image->title, 'url' => $image->url ] : false;
    }

    public function scopeSearchByTitle($query, $title)
    {
        // ilike works only in postgresql
        // return $query->where('title', $title)->orWhere('title', 'ilike', '%' . $title . '%');
        return $query->where('title', $title)->orWhere('title', 'like', '%' . $title . '%');
    }

    public static function defaultImage()
    {
        return (ProductMedia::IMAGES_PATH_PUBLIC . ProductMedia::DEFAULT_IMAGE);
    }

    public static function imageDisplayOrientation($index)
    {
        return array_keys(self::IMAGE_DISPLAY_TYPES)[$index];
    }

    public function isMine()
    {
        return Auth::user()->products()->find($this->id);
    }

    public function marketProduct()
    {
        return MarketProduct::find($this->market_product_id);
    }

    public function publicMarketProduct()
    {
        return $this->is_public ? $this->marketProduct() : null;
    }

    public function categoryName()
    {
        return  ($this->marketProduct() && $this->marketProduct()->category) ? $this->marketProduct()->category->name : 'Uncategorized';
    }

    public function marketPrice()
    {
        return  ($this->marketProduct()) ? $this->marketProduct()->price : 0.0;
    }

    public function marketManufacturer()
    {
        return  ($this->marketProduct()) ? $this->marketProduct()->manufacturer_name : 'Unknown';
    }

	public function sendApprovals()
	{
		return $this->hasMany('MarketPlex\SendApproval');
	}

    public function approved()
    {
        return $this->status == 'APPROVED';
    }

    public function approve()
    {
        $this->status = 'APPROVED';
        if($this->marketProduct())
        {
            $this->marketProduct()->status = $this->status;
            $this->marketProduct()->save();
        }
        return $this->save();
    }
    
    /**
     * Saves discount calculated MRP
     */
    public function saveDiscountedPrice()
    {
        $this->mrp = $this->discountedPrice();
        return $this->save();
    } 
    
    /**
     * Calculates discounted MRP
     */
    public function discountedPrice()
    {
        return $this->marketProduct()->price * ( 1 -  ( $this->discount / 100.0) );
    }

    public function getStatus()
    {
        switch($this->status)
        {
            case 'OUT_OF_STOCK':    return 'Out of stock';
            case 'ON_APPROVAL':     return 'On Approval';
            case 'APPROVED':        return 'Approved';
            case 'REJECTED':        return 'Rejected';
        }
        return 'Unknown';
    }
}
