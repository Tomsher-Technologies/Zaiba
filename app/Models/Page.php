<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Page extends Model
{

  protected $fillable = [
    'type', 'title', 'slug', 'content', 'heading1', 'sub_heading1', 'heading2', 'sub_heading2', 'heading3', 'sub_heading3', 'heading4', 'sub_heading4', 'heading5', 'sub_heading5', 'heading6', 'sub_heading6', 'heading7', 'sub_heading7', 'description', 'title1', 'title2', 'title3', 'title4', 'title5', 'title6', 'image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8', 'image9', 'image10', 'meta_title', 'og_title', 'twitter_title', 'meta_description', 'og_description', 'twitter_description', 'keywords', 'meta_image'
  ];

  public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $page_translation = $this->hasMany(PageTranslation::class)->where('lang', $lang)->first();
      return $page_translation != null ? $page_translation->$field : $this->$field;
  }

  public function page_translations(){
    return $this->hasMany(PageTranslation::class);
  }
}
