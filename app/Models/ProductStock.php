<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class ProductStock extends Model
{
    protected $fillable = ['product_id', 'qty', 'price', 'sku', 'variant', 'image'];
    //
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function wholesalePrices()
    {
        return $this->hasMany(WholesalePrice::class);
    }
    public function image($path)
    {
        return URL::to($path);
    }
}
