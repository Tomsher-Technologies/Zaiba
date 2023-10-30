<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Cart as ModelsCart;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Auth;
use Cache;
use Livewire\Component;

class Cart extends Component
{
    public $carts;
    public $user_col;
    public $user_id;

    public $item_total = 0;
    public $cart_total = 0;
    public $coupon_total = 0;

    public $coupon_code;
    public $coupon_applied = false;
    public $coupon_added = false;

    protected $rules = [
        'coupon_code' => 'required'
    ];

    protected $messages = [
        'coupon_code.required' => 'Please enter a coupon code'
    ];

    function getCarts()
    {

        $this->carts = ModelsCart::where($this->user_col, $this->user_id)->with(['product', 'product.stocks'])->get();
        foreach ($this->carts as $cart) {
            if ($cart->coupon_applied) {
                $this->coupon_code = $cart->coupon_code;
            }
        }
        if ($this->coupon_code) {
            $this->applyCoupon();
        }
    }

    public function mount()
    {
        if (Auth::check()) {
            $this->user_col = 'user_id';
            $this->user_id = Auth::id();
        } else {
            $this->user_col = 'temp_user_id';
            $this->user_id = getTempUserId();
        }

        $this->getCarts();
    }

    public function applyCoupon()
    {
        $this->validate();
        $coupon = Coupon::whereCode($this->coupon_code)->first();

        $can_use_coupon = false;

        if ($coupon) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (Auth::check()) {
                    $coupon_used = CouponUsage::where($this->user_col, $this->user_id)->where('coupon_id', $coupon->id)->first();

                    if ($coupon->one_time_use && $coupon_used != null) {
                        $this->addError('coupon_code', "You already used this coupon!");
                    } else {
                        $can_use_coupon = true;
                    }
                } else {
                    $can_use_coupon = true;
                }
            } else {
                $can_use_coupon = false;
                $this->addError('coupon_code', "Sorry, the coupon has expired!");
            }

            if ($can_use_coupon) {
                $coupon_details = json_decode($coupon->details);

                if ($coupon->type == 'cart_base') {
                    $sum = 0;

                    foreach ($this->carts  as $key => $cartItem) {
                        $sum += $cartItem['price'] * $cartItem['quantity'];
                    }

                    if ($sum >= $coupon_details->min_buy) {
                        if ($coupon->discount_type == 'percent') {
                            $coupon_discount = ($sum * $coupon->discount) / 100;
                            if ($coupon_discount > $coupon_details->max_discount) {
                                $coupon_discount = $coupon_details->max_discount;
                            }
                        } elseif ($coupon->discount_type == 'amount') {
                            $coupon_discount = $coupon->discount;
                        }

                        $this->coupon_added = true;
                    } else {
                        $this->addError('coupon_code', "Sorry, this coupon cannot be applied to this order");
                    }
                } elseif ($coupon->type == 'product_base') {
                    $coupon_discount = 0;
                    foreach ($this->carts as $key => $cartItem) {
                        foreach ($coupon_details as $key => $coupon_detail) {
                            if ($coupon_detail->product_id == $cartItem['product_id']) {
                                if ($coupon->discount_type == 'percent') {
                                    $coupon_discount += ($cartItem['price'] * $coupon->discount / 100) * $cartItem['quantity'];
                                } elseif ($coupon->discount_type == 'amount') {
                                    $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                }
                            }
                        }
                    }
                    $this->coupon_added = true;
                }
            }
        } else {
            $this->addError('coupon_code', "Coupon '$this->coupon_code' does not exist!");
        }

        if ($this->coupon_added && $this->carts->count() > 0){
            ModelsCart::where($this->user_col, $this->user_id)
                ->update(
                    [
                        'discount' => $coupon_discount / $this->carts->count(),
                        'coupon_code' => $this->coupon_code,
                        'coupon_applied' => 1
                    ]
                );
        }
    }

    public function removeCoupon()
    {
        ModelsCart::where($this->user_col, $this->user_id)
            ->update(
                [
                    'discount' => 0,
                    'coupon_code' => null,
                    'coupon_applied' => 0
                ]
            );
        $this->coupon_code = null;
        $this->coupon_added = false;
        $this->coupon_applied = false;
        $this->coupon_total = 0;
    }

    public function remove($id)
    {
        ModelsCart::where([
            'id' => $id,
            $this->user_col => $this->user_id,
        ])->delete();

        Cache::forget('user_cart_count_' . $this->user_id);

        $this->dispatchBrowserEvent('updateCartCount', [
            'count' => cartCount()
        ]);

        $this->emit('cartUpdated');
    }

    public function increment($id)
    {
        $cart = $this->carts->where('id', $id)->first();

        if ($cart->product->stocks->first()->qty > $cart->quantity) {
            $cart->increment('quantity');
            $this->emit('cartUpdated');
        }
    }
    public function decrement($id)
    {
        $cart = $this->carts->where('id', $id)->first();
        if ($cart->product->min_qty < $cart->quantity) {
            $cart->decrement('quantity');
            $this->emit('cartUpdated');
        }
    }

    public function updateQuantity($id, $value)
    {
        $cart = $this->carts->where('id', $id)->first();
        $max = $cart->product->stocks->first()->qty;
        $min = $cart->product->min_qty;
        if ($max < $value) {
            $value = $max;
        }
        if ($min >= $value) {
            $value = $min;
        }
        $cart->quantity = $value;
        $cart->save();
        $this->emit('cartUpdated');
    }

    public function render()
    {
        $this->getCarts();
        return view('livewire.frontend.cart')->extends('frontend.layouts.app');
    }
}
