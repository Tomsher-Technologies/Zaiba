<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductFilterCollection extends ResourceCollection
{

    public function toArray($request)
    {
       return $this->collection->map(function ($data) {
            echo '<pre>';
            // print_r($data->stocks);
            $stock = $data->stocks()->orderBy('metal_weight','asc')->first();
            // print_r($stock);
            // die;
            $priceData = getProductPrice($stock);
            return [
                'id' => $data->id,
                'name' => $data->name,
                'sku' => $data->sku,
                'thumbnail_image' => app('url')->asset($data->thumbnail_img),
                'has_discount' => home_base_price($data, false) != home_discounted_base_price($data, false),
                'stroked_price' => 0,
                'main_price' => 0,
                'price_high_low' => (float)explode('-', home_discounted_base_price($data, false))[0] == (float)explode('-', home_discounted_price($data, false))[1] ? format_price((float)explode('-', home_discounted_price($data, false))[0]) : "From " . format_price((float)explode('-', home_discounted_price($data, false))[0]) . " to " . format_price((float)explode('-', home_discounted_price($data, false))[1]),
                'min_qty' => $data->min_qty,
                'slug' => $data->slug,
                // 'offer_tag' => $priceData['offer_tag']
            ];
        });
    }

    // public function with($request)
    // {
    //     return [
    //         'success' => true,
    //         'status' => 200
    //     ];
    // }
}
