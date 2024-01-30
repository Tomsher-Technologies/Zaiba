<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductFilterCollection extends ResourceCollection
{

    public function toArray($request)
    {
       return $this->collection->map(function ($data) {
            // echo '<pre>';
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
                'stroked_price' => $priceData['original_price'],
                'main_price' => $priceData['discounted_price'],
                'min_qty' => $data->min_qty,
                'slug' => $data->slug,
                'offer_tag' => $priceData['offer_tag']
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
