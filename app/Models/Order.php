<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'combined_order_id', 'code', 'user_id', 'estimated_delivery', 'shipping_address', 'billing_address', 'order_notes', 'delivery_status', 'payment_type', 'payment_status', 'payment_details', 'payment_tracking_id', 'shipping_type', 'shipping_cost', 'tax', 'grand_total', 'sub_total', 'coupon_discount', 'coupon_code', 'offer_discount', 'tracking_code', 'delivery_completed_date', 'date', 'cancel_request', 'cancel_request_date', 'cancel_approval', 'cancel_approval_date', 'cancel_reason'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

}
