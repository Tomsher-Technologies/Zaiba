<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table="menus";

    protected $fillable = ['id','name'];

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu')->with(['image1','image2','image3']);
    }
}
