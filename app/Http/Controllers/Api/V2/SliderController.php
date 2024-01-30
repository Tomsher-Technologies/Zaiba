<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\SliderCollection;
use Cache;

class SliderController extends Controller
{
    public function index()
    {
        return Cache::remember('app.home_slider_images', 86400, function(){
            if(get_setting('home_slider_images') != null && get_setting('home_slider_images') != 'null'){
                return new SliderCollection(json_decode(get_setting('home_slider_images'), true));
            }else{
                return new SliderCollection(array());
            }
            
        });
    }
}
