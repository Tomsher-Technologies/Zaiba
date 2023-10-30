<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'variation',
        'tax',
        'shipping_type',
        'shipping_cost',
        'seller_id',
        'quantity',
        'product_referral_code',
        'product_id',
        'price',
        'pickup_point_id',
        'payment_status',
        'order_id',
        'og_price',
        'delivery_status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function refund_request()
    {
        return $this->hasOne(RefundRequest::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }
}
