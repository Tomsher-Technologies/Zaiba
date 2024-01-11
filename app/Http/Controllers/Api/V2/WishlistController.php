<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\WishlistCollection;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index()
    {
<<<<<<< HEAD
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        if ($user_id != '') {
            $wishlist = Wishlist::with('product')->where('user_id', $user_id)->get();

            $result = [];
            if ($wishlist) {
                foreach ($wishlist as $data) {
                    if (isset($data->product) && !empty($data->product)) {
                        $result[] = [
                            'id' => (int) $data->id,
                            'product' => [
                                'id' => $data->product->id,
                                'name' => $data->product->name,
                                'slug' => $data->product->slug,
                                'thumbnail_image' => app('url')->asset($data->product->thumbnail_img),
                                'has_discount' => home_base_price($data->product, false) != home_discounted_base_price($data->product, false),
                                'stroked_price' => home_base_price($data->product, false),
                                'main_price' => home_discounted_base_price($data->product, false),
                                'price_high_low' => (float)explode('-', home_discounted_base_price($data->product, false))[0] == (float)explode('-', home_discounted_price($data->product, false))[1] ? format_price((float)explode('-', home_discounted_price($data->product, false))[0]) : "From " . format_price((float)explode('-', home_discounted_price($data->product, false))[0]) . " to " . format_price((float)explode('-', home_discounted_price($data->product, false))[1]),
                            ]
                        ];
                    }
                }
            }
=======
        $product_ids = Wishlist::where('user_id', auth()->user()->id)->pluck("product_id")->toArray();
        $existing_product_ids = Product::whereIn('id', $product_ids)->pluck("id")->toArray();
>>>>>>> f02c273fcda02281970e443290a75a6fb1ad2d78

        $query = Wishlist::query();
        $query->where('user_id', auth()->user()->id)->whereIn("product_id", $existing_product_ids);

        return new WishlistCollection($query->latest()->get());
    }

    public function store(Request $request)
    {
        Wishlist::updateOrCreate(
            ['user_id' => $request->user_id, 'product_id' => $request->product_id]
        );
        return response()->json(['message' => translate('Product is successfully added to your wishlist')], 201);
    }

    public function destroy($id)
    {
        try {
            Wishlist::destroy($id);
            return response()->json(['result' => true, 'message' => translate('Product is successfully removed from your wishlist')], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'message' => $e->getMessage()], 200);
        }

    }

    public function add(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->count();
        if ($product > 0) {
            return response()->json([
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);
        } else {
            Wishlist::create(
                ['user_id' =>auth()->user()->id, 'product_id' => $request->product_id]
            );

            return response()->json([
                'message' => translate('Product added to wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);
        }

    }

    public function remove(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' =>  auth()->user()->id])->count();
        if ($product == 0) {
            return response()->json([
                'message' => translate('Product in not in wishlist'),
                'is_in_wishlist' => false,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => 0
            ], 200);
        } else {
            Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->delete();

            return response()->json([
                'message' => translate('Product is removed from wishlist'),
                'is_in_wishlist' => false,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => 0
            ], 200);
        }
    }

    public function isProductInWishlist(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->count();
        if ($product > 0)
            return response()->json([
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (integer)$request->product_id,
                'wishlist_id' => (integer)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);

        return response()->json([
            'message' => translate('Product is not present in wishlist'),
            'is_in_wishlist' => false,
            'product_id' => (integer)$request->product_id,
            'wishlist_id' => 0
        ], 200);
    }
}
