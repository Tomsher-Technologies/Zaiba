<?php

namespace App\Models\Products;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTabs extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'heading',
        'content',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
