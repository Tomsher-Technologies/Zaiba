<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderMenus extends Model
{
    protected $table="header_menus";
    protected $fillable = [
        'category_id',
        'brands'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
