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
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        if ($user_id != '') {
            $wishlist = Wishlist::with('product')->where('user_id', $user_id)->get();
            $result = [];
            if ($wishlist) {
                foreach ($wishlist as $data) {
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

            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $result,
                'wishlist_count' => $this->getWishlistCount($user_id)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $product_slug = $request->has('product_slug') ? $request->product_slug : '';
        $product_id = getProductIdFromSlug($product_slug);
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';

        if ($product_id != '' && $user_id != '') {
            $checkWhishlist =   Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->count();

            if ($checkWhishlist != 0) {
                Wishlist::where('user_id', $user_id)->where('product_id', $product_id)->delete();
            } else {
                Wishlist::create(
                    [
                        'user_id' => $user_id,
                        'product_id' => $product_id
                    ]
                );
            }
            return response()->json([
                'status' => true,
                'wishlist_count' => $this->getWishlistCount($user_id),
                'message' => 'Wishlist updated'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Details not found'
            ], 200);
        }
    }

    public function destroy(Request $request, $id)
    {
        $wishlist = Wishlist::where([
            'user_id' => $request->user()->id
        ])->findOrFail($id);
        $wishlist->delete();

        return response()->json([
            'result' => true,
            'wishlist_count' => $this->getWishlistCount($request->user()->id),
            'message' => translate('Product is successfully removed from your wishlist')
        ], 200);
    }

    public function add(Request $request)
    {
        $product = Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->count();
        if ($product > 0) {
            return response()->json([
                'message' => translate('Product present in wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (int)$request->product_id,
                'wishlist_id' => (int)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);
        } else {
            Wishlist::create(
                ['user_id' => auth()->user()->id, 'product_id' => $request->product_id]
            );

            return response()->json([
                'message' => translate('Product added to wishlist'),
                'is_in_wishlist' => true,
                'product_id' => (int)$request->product_id,
                'wishlist_id' => (int)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);
        }
    }

    public function removeWishlistItem(Request $request)
    {
        $list_ids = $request->list_ids ? explode(',', $request->list_ids) : [];
        $user = getUser();

        if (!empty($list_ids) && $user['users_id'] != '') {
            Wishlist::where('user_id', $user['users_id'])->whereIn('id', $list_ids)->delete();

            return response()->json([
                'status' => true,
                'message' => "Wishlist items removed successfully",
                'wishlist_count' => $this->getWishlistCount($user['users_id']),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Wishlist item not found"
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
                'product_id' => (int)$request->product_id,
                'wishlist_id' => (int)Wishlist::where(['product_id' => $request->product_id, 'user_id' => auth()->user()->id])->first()->id
            ], 200);

        return response()->json([
            'message' => translate('Product is not present in wishlist'),
            'is_in_wishlist' => false,
            'product_id' => (int)$request->product_id,
            'wishlist_id' => 0
        ], 200);
    }


    public function getCount(Request $request)
    {
        return response()->json([
            'status' => true,
            'wishlist_count' => $this->getWishlistCount($request->user()->id),
        ], 200);
    }

    public function getWishlistCount($user)
    {
        return Wishlist::where([
            'user_id' => $user
        ])->count();
    }
}
