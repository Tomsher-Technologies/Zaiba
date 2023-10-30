<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'viewed',
        'user_id',
        'tracking_code',
        'shipping_type',
        'shipping_cost',
        'shipping_address',
        'seller_id',
        'pickup_point_id',
        'payment_type',
        'payment_status_viewed',
        'payment_status',
        'payment_details',
        'guest_id',
        'grand_total',
        'delivery_viewed',
        'delivery_status',
        'date',
        'coupon_discount',
        'commission_calculated',
        'combined_order_id',
        'code',
        'billing_address',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function refund_requests()
    {
        return $this->hasMany(RefundRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->hasOne(Shop::class, 'user_id', 'seller_id');
    }

    public function pickup_point()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function club_point()
    {
        return $this->hasMany(ClubPoint::class);
    }

    public function delivery_boy()
    {
        return $this->belongsTo(User::class, 'assign_delivery_boy', 'id');
    }

    public function proxy_cart_reference_id()
    {
        return $this->hasMany(ProxyPayment::class)->select('reference_id');
    }
}
