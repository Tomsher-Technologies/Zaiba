<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignCategories extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'is_featured', 'slug', 'meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'twitter_title', 'twitter_description', 'is_active'];
}
