<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'link_type', 'link_id', 'image', 'mobile_image', 'offer_type', 'percentage', 'offer_amount', 'buy_amount', 'get_amount', 'start_date', 'end_date', 'status','category_id','slug'
    ];

    protected $casts = [
        'end_date' => 'datetime:Y-m-d h:m:s',
        'start_date' => 'datetime:Y-m-d h:m:s',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
