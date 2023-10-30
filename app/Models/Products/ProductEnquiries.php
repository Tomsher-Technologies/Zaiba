<?php

namespace App\Models\Products;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEnquiries extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'temp_user_id',
        'comment',
        'status',
        'name',
        'email',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_product_enquiry', 'product_enquiry_id','product_id');
    }
}
