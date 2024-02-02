<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductFilterCollection extends ResourceCollection
{

    public function toArray($request)
    {
       return $this->collection->map(function ($data) {
            
            return [
                'id' => $data->id ?? '',
                'product_id' => $data->product_id ?? '',
                'name' => $data->product->name ?? '',
                'sku' => $data->sku ?? '',
                'thumbnail_image' => ($data->image != NULL && $data->image != '0') ? get_product_image($data->image,'300') : get_product_image($data->product->thumbnail_img,'300'),
                'stroked_price' => $data->price ?? 0,
                'main_price' => $data->offer_price ?? 0,
                'min_qty' => $data->product->min_qty ?? 0,
                'quantity' => $data->qty ?? 0,
                'slug' => $data->product->slug ?? '',
                'offer_tag' => $data->offer_tag ?? '',
                'attributes' => getProductAttributes($data->attributes)
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
