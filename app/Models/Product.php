<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;
use App\Models\Products\ProductDetails;
use App\Models\Products\ProductEnquiries;
use App\Models\Products\ProductTabs;
use Cache;
use Illuminate\Support\Str;
use URL;
use Wildside\Userstamps\Userstamps;

class Product extends Model
{
    use Userstamps;

    protected $fillable = [
        'name',
        'sku',
        'added_by',
        'user_id',
        'category_id',
        'brand_id',
        'video_provider',
        'video_link',
        'description',
        'unit_price',
        'purchase_price',
        'unit',
        'slug',
        'approved',
        'colors',
        'choice_options',
        'variations',
        'photos',
        'thumbnail_img',
        'return_refund',
        'length',
        'height',
        'width',
        'weight',
        'hide_price',
        'part_number',
    ];

    // protected $with = ['taxes'];
    // protected $with = ['product_translations', 'taxes'];

    public function seo()
    {
        return $this->hasOne(ProductSeo::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function taxes()
    {
        return $this->hasMany(ProductTax::class);
    }

    public function flash_deal_product()
    {
        return $this->hasOne(FlashDealProduct::class);
    }

    public function tabs()
    {
        return $this->hasMany(ProductTabs::class);
    }

    // public function enquiries()
    // {
    //     return $this->belongsToMany(ProductEnquiries::class, 'product_product_enquiry');
    // }

    private function generateSlug($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function ($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }

    public function image($path)
    {
        return URL::to($path);
    }

    // public function thumbnail()
    // {
    //     return $this->hasOne(Upload::class, 'id', 'thumbnail_img');
    // }

    // public function gallery()
    // {
    //     return $this->hasMany(Upload::class, 'id', 'photos');
    // }

    // public function getGalleryAttributes()
    // {
    //     $photos = $this->getOriginal('photos');
    //     return  explode(',', $photos);
    // }

    // public function allCategories()
    // {
    //     $parents = collect([]);
    //     $parent = $this->parentCategory;
    //     while (!is_null($parent)) {
    //         $parents->push($parent);
    //         $parent = $parent->parent;
    //     }

    //     return $parents;
    // }

    public static function boot()
    {
        static::creating(function ($model) {
            Cache::forget('newest_products');
        });
        parent::boot();
    }
}
