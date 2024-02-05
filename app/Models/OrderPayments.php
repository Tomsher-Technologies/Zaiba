<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayments extends Model
{
    protected $fillable = [
        'order_id', 'payment_status', 'payment_details'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
