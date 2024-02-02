<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    
    protected $fillable = ['user_id', 'temp_user_id', 'product_id', 'product_stock_id', 'variation', 'quantity', 'metal_price', 'stone_price', 'making_charge', 'price', 'offer_price', 'offer_id', 'offer_tag', 'tax', 'shipping_cost', 'shipping_type', 'discount', 'offer_discount', 'coupon_code', 'coupon_applied', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_stock()
    {
        return $this->belongsTo(ProductStock::class);
    }
}
