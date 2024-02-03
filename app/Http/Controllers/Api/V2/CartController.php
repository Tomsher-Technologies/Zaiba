<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CartCollection;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user_id = '';
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            if ($request->header('UserToken')) {
                Cart::where('temp_user_id', $request->header('UserToken'))
                    ->update([
                            'user_id' => $user_id,
                            'temp_user_id' => null
                    ]);
            }
            $carts = Cart::where('user_id', $user_id)->orderBy('id','asc')->get();
            
            if(!empty($carts[0])){
                $carts->load(['product', 'product_stock']);
            }
        } else {
            $temp_user_id = $request->header('UserToken');
            $carts = ($temp_user_id != null) ? Cart::where('temp_user_id', $temp_user_id)->orderBy('id','asc')->get() : [];
    
            if(!empty($carts[0])){
                $carts->load(['product', 'product_stock']);
            }
        }

        $result = [];
        $sub_total = $discount = $shipping = $coupon_display = $coupon_discount = $offerIdCount = $total_coupon_discount = 0;
        $coupon_code = $coupon_applied = null;

        $overall_subtotal = $total_discount = $total_tax = $total_shipping = $cart_coupon_discount = 0;
        $cart_coupon_code = $cart_coupon_applied = NULL;
        
        if(!empty($carts[0])){
            $off = [];

            foreach($carts as $data){
                $updateCart                 = Cart::find($data->id);
                $updateCart->metal_price    = $data->product_stock->metal_price_break ?? 0;
                $updateCart->stone_price    = $data->product_stock->stone_price ?? 0;
                $updateCart->making_charge  = $data->product_stock->making_price_break ?? 0;
                $updateCart->price          = $data->product_stock->price ?? 0;
                $updateCart->offer_price    = $data->product_stock->offer_price ?? 0;
                $updateCart->offer_tag      = $data->product_stock->offer_tag ?? NULL;
                $updateCart->tax            = 0;
                $updateCart->discount       = 0.00;
                $updateCart->save();
            }
        
            $carts = $carts->fresh();

            $coupon_code = $carts[0]->coupon_code;
            if ($coupon_code) {
                $coupon = Coupon::whereCode($coupon_code)->first();
                $can_use_coupon = false;
                if ($coupon) {               
                    if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                        if($user_id != ''){
                            if($coupon->one_time_use == 1){
                                $coupon_used = CouponUsage::where('user_id', $user_id)->where('coupon_id', $coupon->id)->first();
                                if ($coupon_used == null) {
                                    $can_use_coupon = true;
                                }
                            }else{
                                $can_use_coupon = true;
                            }
                        }
                    } else {
                        $can_use_coupon = false;
                    }
                }
                if ($can_use_coupon) {
                    $coupon_details = json_decode($coupon->details);
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $subtotal += $cartItem['offer_price'] * $cartItem['quantity'];
                            $tax += $cartItem['tax'];
                            $shipping += $cartItem['shipping'] ;
                        }

                        $sum = $subtotal + $tax + $shipping;

                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }

                            if($user_id != ''){
                                Cart::where('user_id', $user_id)->update([
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $coupon_code,
                                    'coupon_applied' => 1
                                ]);
                            } 
                        }
                    }elseif ($coupon->type == 'product_base') {
                        $coupon_discount = 0;
                        foreach ($carts as $key => $cartItem) {
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += ($cartItem['offer_price'] * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }

                        if($user_id != ''){
                            Cart::where('user_id', $user_id)->update([
                                'discount' => $coupon_discount / count($carts),
                                'coupon_code' => $coupon_code,
                                'coupon_applied' => 1
                            ]);
                        }
                    }
                }else{
                    Cart::where('user_id', $user_id)->update([
                        'discount' => 0.00,
                        'coupon_code' => NULL,
                        'coupon_applied' => 0
                    ]);
                }
            }else{
                if($user_id != ''){
                    Cart::where('user_id', $user_id)->update([
                        'discount' => 0.00,
                        'coupon_code' => NULL,
                        'coupon_applied' => 0
                    ]);
                }
                $coupon_code = '';
                $coupon_applied = 0;
                $total_coupon_discount = 0;
            }
        
            $carts = $carts->fresh();
            $newOfferCartCount = 0;

           
            foreach($carts as $datas){

                $overall_subtotal = $overall_subtotal + ($datas->price * $datas->quantity);

                $total_discount = $total_discount + (($datas->price * $datas->quantity) - ($datas->offer_price * $datas->quantity)) + $datas->offer_discount;
                $total_tax = $total_tax + $datas->tax;

                $result['products'][] = [
                    'id' => $datas->id,
                    'product' => [
                        'id' => $datas->product->id,
                        'product_variant_id' => $datas->product_stock->id,
                        'name' => $datas->product->name,
                        'slug' => $datas->product->slug,
                        'sku' => $datas->product_stock->sku,
                        'image' => ($datas->product_stock->image != NULL && $datas->product_stock->image != '0') ? get_product_image($datas->product_stock->image,'300') : get_product_image($datas->product->thumbnail_img,'300'),
                        'price_breakup' => array( "gold" => $datas->product_stock->metal_price_break ?? 0,
                                                    "making_charge" =>  $datas->product_stock->making_price_break ?? 0,
                                                    "stone_price" => $datas->product_stock->stone_price ?? 0
                                            ),
                        'attributes' => getProductAttributes($datas->product_stock->attributes)
                    ],
                    
                    'stroked_price' => $datas->price ,
                    'main_price' => $datas->offer_price ,
                    'tax' => $datas->tax,
                    'offer_tag' => $datas->offer_tag,
                    'quantity' => (integer) $datas->quantity,
                    'date' => $datas->created_at->diffForHumans(),
                    'total' => $datas->offer_price * $datas->quantity
                ];
                $cart_coupon_code = $datas->coupon_code;
                $cart_coupon_applied = $datas->coupon_applied;
                if($datas->coupon_applied == 1){
                    $cart_coupon_discount += $datas->discount;
                    $coupon_display++;
                }
                // if($datas->offer_tag != ''){
                //     $coupon_display++;
                // }
            }

        }else{
            $result['products'] = [];
        }

        $cart_total = ($overall_subtotal + $total_tax) - ($total_discount + $cart_coupon_discount);

        $freeShippingStatus = get_setting('free_shipping_status');
        $freeShippingLimit = get_setting('free_shipping_min_amount');
        $defaultShippingCharge = get_setting('default_shipping_amount');
        $cartCount = count($carts);

        if($freeShippingStatus == 1){
            if($cart_total >= $freeShippingLimit){
                $total_shipping = 0;
                Cart::where('user_id', $user_id)->update([
                    'shipping_cost' => 0,
                    'shipping_type' => 'free'
                ]);
            }else{
                $total_shipping = $defaultShippingCharge;
                if($user_id != '' && $defaultShippingCharge > 0 && $cartCount != 0){
                    Cart::where('user_id', $user_id)->update([
                        'shipping_cost' => $defaultShippingCharge / $cartCount,
                        'shipping_type' => 'paid'
                    ]);
                }
            }
        }else{
            $total_shipping = $defaultShippingCharge;
            if($user_id != '' && $defaultShippingCharge > 0 && $cartCount != 0){
                Cart::where('user_id', $user_id)->update([
                    'shipping_cost' => $defaultShippingCharge / $cartCount,
                    'shipping_type' => 'paid'
                ]);
            }
        }

        $cart_total = ($overall_subtotal + $total_shipping + $total_tax) - ($total_discount + $cart_coupon_discount);

        $result['summary'] = [
            'sub_total' => $overall_subtotal,
            'discount' => $total_discount, // Discount is in amount
            'shipping' => $total_shipping,
            'vat_amount' => $total_tax,
            'total' => $cart_total,
            // 'coupon_display' => ($coupon_display === 0) ? 1 : 0,
            'coupon_code' => $cart_coupon_code,
            'coupon_applied' => $cart_coupon_applied,
            'coupon_discount' => $cart_coupon_discount
        ];
        
        return response()->json(['status' => true,"message"=>"Success","data" => $result],200);
    }
    public function store(Request $request)
    {
        $product_slug   = $request->has('product_slug') ? $request->product_slug : '';
        $sku            = $request->has('sku') ? $request->sku : '';
        $quantity       = $request->has('quantity') ? $request->quantity : 0;

        $user = getUser();

        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            if ($request->header('UserToken')) {
                Cart::where('temp_user_id', $request->header('UserToken'))
                    ->update(
                        [
                            'user_id' => $user_id,
                            'temp_user_id' => null
                        ]
                    );
            }
        }

        $variantProduct = ProductStock::leftJoin('products as p','p.id','=','product_stocks.product_id')
                                    ->where('product_stocks.sku', $sku)
                                    ->where('p.slug', $product_slug)
                                    ->select('product_stocks.*')->first() ?? [];

        if(!empty($variantProduct) && $user['users_id'] != ''){
            $product_id         = $variantProduct['product_id'] ?? null;
            $product_stock_id   = $variantProduct['id'] ?? null;
          
            $current_Stock      = $variantProduct['qty'] ?? 0;
            if ($current_Stock < $quantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'This item is out of stock!',
                    'cart_count' => $this->cartCount()
                ], 200);
            }

            $carts = Cart::where([
                $user['users_id_type'] => $user['users_id'],
                'product_id' => $product_id,
                'product_stock_id' => $product_stock_id
            ])->first();

            $tax = 0;
            if ($carts) {
                // if($product->vat != 0){
                //     $new_quantity = $carts->quantity + $quantity;
                //     $tax = (($carts->offer_price * $new_quantity)/100) * $product->vat;
                // }
                $carts->quantity        += $quantity;
                $carts->tax             = $tax;
                $carts->metal_price     = $variantProduct->metal_price_break ?? 0;
                $carts->stone_price     = $variantProduct->stone_price ?? 0;
                $carts->making_charge   = $variantProduct->making_price_break ?? 0;
                $carts->price           = $variantProduct->price ?? 0;
                $carts->offer_price     = $variantProduct->offer_price ?? 0;
                $carts->offer_tag       = $variantProduct->offer_tag ?? NULL;
                $carts->save();
            }else {
               
                // if($product->vat != 0){
                //     $tax = (($variantProduct->offer_price * ($quantity ?? 1))/100) * $product->vat;
                // }
                $data[$user['users_id_type']]   =  $user['users_id'];
                $data['product_id']             = $product_id;
                $data['product_stock_id']       = $product_stock_id;
                $data['quantity']               = $quantity;
                $data['metal_price']            = $variantProduct->metal_price_break ?? 0;
                $data['stone_price']            = $variantProduct->stone_price ?? 0;
                $data['making_charge']          = $variantProduct->making_price_break ?? 0;
                $data['price']                  = $variantProduct->price ?? 0;
                $data['offer_price']            = $variantProduct->offer_price ?? 0;
                $data['offer_tag']              = $variantProduct->offer_tag ?? NULL;
                $data['tax']                    = $tax;
                $data['shipping_cost']          = 0;

                Cart::create($data);
            }

            return response()->json([
                'success' => true,
                'message' => "Cart updated successfully",
                'cart_count' =>  $this->cartCount()
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => "Failed to add item to the cart",
                'cart_count' => $this->cartCount()
            ], 200); 
        }
    }

    public function destroy(Request $request, $id)
    {
        
    }


    public function getCount(Request $request)
    {
        return response()->json([
            'success' => true,
            'cart_count' => $this->cartCount(),
        ]);
    }

    public function cartCount()
    {
        $user = getUser();

        return Cart::where([
            $user['users_id_type'] => $user['users_id']
        ])->count();
    }

    public function removeCartItem(Request $request)
    {
        $cart_ids = $request->cart_ids ? explode(',', $request->cart_ids) : [];
        $user = getUser();

        if (!empty($cart_ids) && $user['users_id'] != '') {
            Cart::where([
                $user['users_id_type'] => $user['users_id']
            ])->whereIn('id', $cart_ids)->delete();

            return response()->json([
                'status' => true,
                'message' => "Cart items removed successfully"
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Cart item not found"
            ], 200);
        }
    }

    public function changeQuantity(Request $request)
    {
        $cart_id    = $request->cart_id ?? '';
        $quantity   = $request->quantity ?? '';
        $action     = $request->action ?? '';
        $user       = getUser();

        if($cart_id != '' && $quantity != '' && $action != '' && $user['users_id'] != ''){
            $cart = Cart::where([
                $user['users_id_type'] => $user['users_id']
            ])->with([
                'product',
                'product_stock',
            ])->findOrFail($request->cart_id);
    
            $max_qty = $cart->product_stock->qty;

            if ($action == 'plus') {           // Increase quantity of a product in the cart.
                if ( $quantity <= $max_qty) {
                    $cart->quantity = $quantity;   // Update quantity of a product in the cart.
                    $cart->save();
                    return response()->json([
                        'status'    => true,
                        'message'   => "Cart updated",
                    ], 200);
                }else{
                    return response()->json([
                        'status'    => false,
                        'message'   => "Maximum quantity reached",
                    ], 200);
                }
            }elseif($action == 'minus'){   // Decrease quantity of a product in the cart. If it reaches zero then delete that row from the table.
                if($quantity < 1){
                    Cart::where('id',$cart->id)->delete();
                }else{
                    $cart->quantity = $quantity;        // Update quantity of a product in the cart.
                    $cart->save();
                }
                return response()->json([
                    'status'    => true,
                    'message'   => "Cart updated",
                ], 200);
            }else{
                return response()->json([
                    'status'    => false,
                    'message'   => "Undefined action value",
                ], 200);
            }
        } else {
            return response()->json([
                'status'    => false,
                'message'   => "Missing data"
            ], 200);
        }
    }
}
