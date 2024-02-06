<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use URL;

class Blog extends Model
{

    protected $fillable = [
        'title', 'slug', 'description', 'image', 'blog_date', 'status', 'seo_title', 'og_title', 'twitter_title', 'seo_description', 'og_description', 'twitter_description', 'keywords'
      ];

    public function image($path)
    {
        return URL::to($path);
    }
}
