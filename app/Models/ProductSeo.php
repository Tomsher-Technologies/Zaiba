<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        
        'meta_title',
        'meta_description',
        'meta_keywords',

        'og_title',
        'og_description',

        'twitter_title',
        'twitter_description',
        
        'lang',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
