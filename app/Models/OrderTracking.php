<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    public $table = 'order_tracking';

    protected $fillable = [
        'order_id', 'status', 'description', 'status_date'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}
