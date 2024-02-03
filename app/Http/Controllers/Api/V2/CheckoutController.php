<?php


namespace App\Http\Controllers\Api\V2;


use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use App\Models\Cart;

class CheckoutController
{
    public function apply_coupon_code(Request $request)
    {
        $user = getUser();
        // print_r($user);
        if($user['users_id'] != ''){
            $cart_items = Cart::where([$user['users_id_type'] => $user['users_id']])->get();
            $cartCount = count($cart_items);
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if ($cart_items->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => translate('Cart is empty')
                ], 200);
            }

            if ($coupon == null) {
                return response()->json([
                    'status' => false,
                    'message' => translate('Invalid coupon code!')
                ], 200);
            }

            $in_range = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;

            if (!$in_range) {
                return response()->json([
                    'status' => false,
                    'message' => translate('Coupon expired!')
                ], 200);
            }

            if($coupon->one_time_use == 1){
                $is_used = CouponUsage::where('user_id', $user['users_id'])->where('coupon_id', $coupon->id)->first() != null;

                if ($is_used) {
                    return response()->json([
                        'status' => false,
                        'message' => translate('You already used this coupon!')
                    ], 200);
                }
            }

            $coupon_details = json_decode($coupon->details);

            if ($coupon->type == 'cart_base') {
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;
                foreach ($cart_items as $key => $cartItem) {
                    $subtotal += $cartItem['offer_price'] * $cartItem['quantity'];
                    $tax += $cartItem['tax'];
                    $shipping += $cartItem['shipping'];
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

                    Cart::where('user_id', $user['users_id'])->update([
                        'discount' => $coupon_discount / $cartCount,
                        'coupon_code' => $request->coupon_code,
                        'coupon_applied' => 1
                    ]);

                    return response()->json([
                        'status' => true,
                        'message' => translate('Coupon Applied')
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => translate('Sorry, this coupon cannot be applied to this order')
                    ], 200);
                }
            } elseif ($coupon->type == 'product_base') {
                $coupon_discount = 0;

                foreach ($cart_items as $key => $cartItem) {
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

                Cart::where('user_id', $user['users_id'])->update([
                    'discount' => $coupon_discount / $cartCount,
                    'coupon_code' => $request->coupon_code,
                    'coupon_applied' => 1
                ]);

                return response()->json([
                    'status' => true,
                    'message' => translate('Coupon Applied')
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 200);
        }
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', auth()->user()->id)->update([
            'discount' => 0.00,
            'coupon_code' => "",
            'coupon_applied' => 0
        ]);

        return response()->json([
            'status' => true,
            'message' => translate('Coupon Removed')
        ], 200);
    }
}
