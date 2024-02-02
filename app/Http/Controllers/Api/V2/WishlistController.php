<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\WishlistCollection;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index(Request $request)
    {
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        if($user_id != ''){
            $wishlist = Wishlist::with('product','product_stock')->where('user_id', $user_id)->get();
           
            $result = [];
            if($wishlist){
                foreach($wishlist as $data){
                    if($data->product && $data->product_stock){
                        $result[] = [
                            'id' => (int) $data->id,
                            'product' => [
                                'variant_id' => $data->product_stock->id ?? '',
                                'product_id' => $data->product_id ?? '',
                                'name' => $data->product->name ?? '',
                                'sku' => $data->product_stock->sku ?? '',
                                'slug' => $data->product->slug ?? '',
                                'thumbnail_image' => ($data->product_stock->image != NULL && $data->product_stock->image != '0') ? get_product_image($data->product_stock->image,'300') : get_product_image($data->product->thumbnail_img,'300'),
                                'stroked_price' => $data->product_stock->price ?? 0,
                                'main_price' => $data->product_stock->offer_price ?? 0,
                                'min_qty' => $data->product->min_qty ?? 0,
                                'quantity' => $data->product_stock->qty ?? 0,
                                'offer_tag' => $data->product_stock->offer_tag ?? '',
                                'attributes' => getProductAttributes($data->product_stock->attributes)
                            ]
                        ];
                    }
                }    
            }
        
            return response()->json([
                'status' => true,
                'message' => 'Success',
                'data' => $result,
                'wishlist_count' => count($result)
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $product_slug   = $request->has('product_slug') ? $request->product_slug : '';
        $sku            = $request->has('sku') ? $request->sku : '';
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        
        $variantProduct = ProductStock::leftJoin('products as p','p.id','=','product_stocks.product_id')
                                    ->where('product_stocks.sku', $sku)
                                    ->where('p.slug', $product_slug)
                                    ->select('product_stocks.*')->first()?->toArray();
                                    
        if(!empty($variantProduct)){
            $product_id         = $variantProduct['product_id'] ?? null;
            $product_stock_id   = $variantProduct['id'] ?? null;
            if($product_id != null &&  $product_stock_id != null){
                // Check if product already exist in wishlist
                $checkWhishlist =   Wishlist::where('user_id',$user_id)
                                            ->where('product_id',$product_id)
                                            ->where('product_stock_id',$product_stock_id)->count();

                if($checkWhishlist != 0){
                    Wishlist::where('user_id',$user_id)->where('product_id',$product_id)
                            ->where('product_stock_id',$product_stock_id)->delete();
                }else{
                    Wishlist::create([
                            'user_id' => $user_id,
                            'product_id' => $product_id,
                            'product_stock_id' => $product_stock_id
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'wishlist_count' => $this->getWishlistCount($user_id),
                    'message' => 'Wishlist updated'
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Details not found'
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Details not found'
            ], 200);
        }
    }

    public function getWishlistCount($user)
    {
        return Wishlist::where([
            'user_id' => $user
        ])->count();
    }

    public function getCount(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Success',
            'wishlist_count' => $this->getWishlistCount($request->user()->id),
        ], 200);
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

    public function removeWishlistItem(Request $request){
        $list_ids = $request->list_ids ? explode(',', $request->list_ids) : [];
        $user = getUser();

        if(!empty($list_ids) && $user['users_id'] != ''){
            Wishlist::where('user_id', $user['users_id'])->whereIn('id',$list_ids)->delete();

            return response()->json([
                'status' => true,
                'message' => "Wishlist items removed successfully",
                'wishlist_count' => $this->getWishlistCount($user['users_id']),
            ], 200);
        }else {
            return response()->json([
                'status' => false,
                'message' => "Wishlist item not found"
            ], 200);
        }
    }

}
