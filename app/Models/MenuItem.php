<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table="menu_items";

    protected $fillable = ['label', 'link', 'img_1', 'img_1_link', 'img_2', 'img_2_link', 'img_3', 'img_3_link', 'brands', 'parent', 'sort', 'class', 'menu', 'depth'];

    public function image1(){
    	return $this->belongsTo(Upload::class,'img_1','id');
    }

    public function image2(){
    	return $this->belongsTo(Upload::class,'img_2','id');
    }

    public function image3(){
    	return $this->belongsTo(Upload::class,'img_3','id');
    }
}
