<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cache;

class Designs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'is_featured',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
      ];
    
      public function products()
      {
        return $this->hasMany(Product::class, '_id');
      }
    
      public function logoImage()
      {
        return $this->hasOne(Upload::class, 'id', 'logo');
      }
    
      public static function boot()
      {
        static::creating(function ($model) {
          Cache::forget('designs');
        });
    
        static::updating(function ($model) {
          Cache::forget('designs');
        });
    
        static::deleting(function ($model) {
          Cache::forget('designs');
        });
    
        parent::boot();
      }
}
