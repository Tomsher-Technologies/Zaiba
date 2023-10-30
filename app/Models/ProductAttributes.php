<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'product_varient_id', 
        'attribute_id', 
        'attribute_value_id'
      ];
    
}
