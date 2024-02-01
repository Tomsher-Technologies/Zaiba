<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'status', 'variant', 'sku', 'description', 'metal_weight', 'stone_available', 'stone_type', 'stone_count', 'stone_weight', 'stone_price', 'making_price_type', 'making_charge', 'price', 'offer_price', 'metal_price_break', 'making_price_break','offer_tag', 'qty', 'image','created_at','updated_at'];
    //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wholesalePrices()
    {
        return $this->hasMany(WholesalePrice::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttributes::class,'product_varient_id','id');
    }

    public function image($path)
    {
        return URL::to($path);
    }
}
