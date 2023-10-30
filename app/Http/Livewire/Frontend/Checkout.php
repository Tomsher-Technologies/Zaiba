<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Controllers\CheckoutController;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CombinedOrder;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderDetail;
use Auth;
use Livewire\Component;
use Mpdf\Tag\Th;

class Checkout extends Component
{
    public $current_step = 1;

    public $user_col = "";
    public $user_id = "";
    public $carts;
    protected $country_list;
    public $addresses;
    public $shipping_rates;
    public $payment_methods;

    public $btn_disabled = 0;

    public $sub_total = 0;
    public $coupon_rate = 0;
    public $shipping_rate = 0;
    public $copupn_applied = false;
    public $total = 0;


    // New Address field
    public $new_address_lat;
    public $new_address_long;
    public $new_address_name;
    public $new_address_address;
    public $new_address_country;
    public $new_address_state;
    public $new_address_city;
    public $new_address_pincode;
    public $new_address_phone;

    // Billing address
    public $billing_address_name;
    public $billing_address_address;
    public $billing_address_country;
    public $billing_address_state;
    public $billing_address_city;
    public $billing_address_pincode;
    public $billing_address_phone;

    // Checkout data
    public $shipping_address;
    public $diffrent_billing_address = false;
    public $shipping_method;
    public $payment_method;

    // Guest data
    public $guest_address_lat;
    public $guest_address_long;
    public $guest_address_name;
    public $guest_address_email;
    public $guest_address_address;
    public $guest_address_country;
    public $guest_address_state;
    public $guest_address_city;
    public $guest_address_pincode;
    public $guest_address_phone;

    public function mount()
    {
        if (Auth::check()) {
            $this->user_col = "user_id";
            $this->user_id = Auth::id();
        } else {
            $this->user_col = "temp_user_id";
            $this->user_id = getTempUserId();
        }

        $this->carts = Cart::where($this->user_col, $this->user_id)->with('product')->get();

        if ($this->carts->count()) {
            // Apply coupons
            $coupon_code = null;
            foreach ($this->carts as $cart) {
                $this->sub_total += $cart->quantity * $cart->price;
                if ($cart->coupon_applied) {
                    $this->coupon_rate += $cart->discount;
                    $coupon_code = $cart->coupon_code;
                    $this->copupn_applied  = true;
                }
            }

            if ($coupon_code) {
                $coupon = Coupon::whereCode($coupon_code)->first();
                if ($coupon) {
                    $can_use_coupon = false;
                    if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                        if (Auth::check()) {
                            $coupon_used = CouponUsage::where($this->user_col, $this->user_id)->where('coupon_id', $coupon->id)->first();
                            if ($coupon->one_time_use && $coupon_used == null) {
                                $can_use_coupon = true;
                            }
                        } else {
                            $can_use_coupon = true;
                        }
                    } else {
                        $can_use_coupon = false;
                    }
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
                    }

                    $this->copupn_applied = true;
                }
            }
        } else {
            return redirect()->route('cart');
        }

        if ($this->carts->count() && Auth::check()) {
            $this->addresses = Address::with([
                'country',
                'city',
                'state',
            ])->whereUserId($this->user_id)->orderBy('set_default', 'desc')->get();

            if ($this->addresses && $this->addresses->where('set_default', true)->first()) {
                $this->shipping_address = $this->addresses->where('set_default', true)->first()->id;
            }
        }

        if (get_setting('free_shipping_status')) {
            if (
                $this->sub_total > get_setting('free_shipping_min_amount') &&
                $this->sub_total <= get_setting('free_shipping_max_amount')
            ) {
                $this->shipping_rates['free_shipping']['name'] = "Free Shipping";
                $this->shipping_rates['free_shipping']['rate'] = 0;
            }
        }

        if (get_setting('shipping_type') == 'flat_rate') {
            $this->shipping_rates['falt_rate']['name'] = "Flat Rate Shipping";
            $this->shipping_rates['falt_rate']['rate'] = get_setting('flat_rate_shipping_cost');
        }

        if (get_setting('pickup_from_store') == 'on') {
            $this->shipping_rates['pickup_from_store']['name'] = "Pick up from store";
            $this->shipping_rates['pickup_from_store']['rate'] = 0;
            $this->shipping_rates['pickup_from_store']['note'] = "For international shipments, contact <a href='mailto:info@industrytechstore.com'>info@industrytechstore.com</a> for courier charges.";
        }

        if (get_setting('cod_status')) {
            $this->payment_methods['cod']['name'] = "Cash On Delivery";
            $this->payment_methods['cod']['type'] = 'cash_on_delivery';
        }

        $this->payment_method = $this->payment_methods[key($this->payment_methods)]['type'];

        $this->country_list = Country::whereStatus(1)->get();
    }


    public function saveAddress()
    {
        $validatedData = $this->validate([
            'new_address_lat' => 'required',
            'new_address_long' => 'required',
            'new_address_name' => 'required',
            'new_address_address' => 'required',
            'new_address_country' => 'required',
            'new_address_state' => 'required',
            'new_address_city' => 'required',
            'new_address_pincode' => 'nullable',
            'new_address_phone' => 'required',
        ], [
            'new_address_lat.required' => "Please choose your location",
            'new_address_long.required' => "Please choose your location",
            'new_address_name.required' => "Please enter a name",
            'new_address_address.required' => "Please enter your address",
            'new_address_country.required' => "Please choose a country",
            'new_address_state.required' => "Please choose a state",
            'new_address_city.required' => "Please choose a city",
            'new_address_pincode.required' => "Please enter a pincode",
            'new_address_phone.required' => "Please enter your phone number",
        ]);

        $address = Address::create([
            'user_id' => Auth::user()->id,
            'name' => $this->new_address_name,
            'address' => $this->new_address_address,
            'country_id' => $this->new_address_country,
            'state_id' => $this->new_address_state,
            'city_id' => $this->new_address_city,
            'longitude' => $this->new_address_lat,
            'latitude' => $this->new_address_long,
            'postal_code' => $this->new_address_pincode,
            'phone' => $this->new_address_phone,
            'set_default' => 0,
        ]);

        $this->dispatchBrowserEvent('addressAdded');

        $this->reset([
            'new_address_lat',
            'new_address_long',
            'new_address_name',
            'new_address_address',
            'new_address_country',
            'new_address_state',
            'new_address_city',
            'new_address_pincode',
            'new_address_phone',
        ]);

        $this->addresses = Address::with([
            'country',
            'city',
            'state',
        ])->whereUserId($this->user_id)->orderBy('set_default', 'desc')->get();
    }

    public function step1()
    {
        $this->current_step = 1;
        // $this->dispatchBrowserEvent('showStep', 1);
    }

    public function step2()
    {
        if (Auth::check()) {
            $validatedData = $this->validate([
                'shipping_address' => 'required',
            ], [
                'shipping_address.required' => "Please select a shipping address",
            ]);
        } else {
            $validatedData = $this->validate([
                'guest_address_lat' => 'required',
                'guest_address_long' => 'required',
                'guest_address_name' => 'required',
                'guest_address_email' => 'required|email',
                'guest_address_address' => 'required',
                'guest_address_country' => 'required',
                'guest_address_state' => 'required',
                'guest_address_city' => 'required',
                'guest_address_pincode' => 'nullable',
                'guest_address_phone' => 'required',
            ], [
                'guest_address_lat.required' => "Please choose your location",
                'guest_address_long.required' => "Please choose your location",
                'guest_address_name.required' => "Please enter a name",
                'guest_address_email.required' => "Please enter your email",
                'guest_address_email.email' => "Please enter a valid email address",
                'guest_address_address.required' => "Please enter your address",
                'guest_address_country.required' => "Please choose a country",
                'guest_address_state.required' => "Please choose a state",
                'guest_address_city.required' => "Please choose a city",
                'guest_address_pincode.required' => "Please enter a pincode",
                'guest_address_phone.required' => "Please enter your phone number",
            ]);
        }

        $country_code = '';
        if (Auth::check()) {
            $address = $this->addresses->where('id', $this->shipping_address)->first();
            $country_code = $address->country->code;
        } else {
            $country_code = Country::where('id', $this->guest_address_country)->first()->code;
        }

        if ($country_code !== 'AE') {
            if (isset($this->shipping_rates['falt_rate'])) {
                unset($this->shipping_rates['falt_rate']);
            }
        } else {
            if (!isset($this->shipping_rates['falt_rate'])) {
                $this->shipping_rates['falt_rate']['name'] = "Flat Rate Shipping";
                $this->shipping_rates['falt_rate']['rate'] = get_setting('flat_rate_shipping_cost');
            }
        }

        $this->shipping_method = key($this->shipping_rates);
        $this->shipping_rate = $this->shipping_rates[$this->shipping_method]['rate'];

        if ($this->diffrent_billing_address) {
            $this->current_step = 2;
        } else {
            $this->current_step = 3;
        }
    }

    public function step3()
    {
        $validatedData = $this->validate([
            'billing_address_name' => 'required',
            'billing_address_address' => 'required',
            'billing_address_country' => 'required',
            'billing_address_state' => 'required',
            'billing_address_city' => 'required',
            'billing_address_pincode' => 'nullable',
            'billing_address_phone' => 'required',
        ], [
            'billing_address_name.required' => "Please enter a name",
            'billing_address_address.required' => "Please enter your address",
            'billing_address_country.required' => "Please choose a country",
            'billing_address_state.required' => "Please choose a state",
            'billing_address_city.required' => "Please choose a city",
            'billing_address_pincode.required' => "Please enter a pincode",
            'billing_address_phone.required' => "Please enter your phone number",
        ]);

        $this->current_step = 3;

        // $this->dispatchBrowserEvent('showStep', 3);
    }

    public function step4()
    {
        $this->current_step = 4;

        $validatedData = $this->validate([
            'shipping_method' => 'required',
        ], [
            'shipping_method.required' => "Please select a shipping method",
        ]);
    }

    public function checkout()
    {
        $shipping_address_json = [];
        $billing_address_json = [];

        if (Auth::check()) {
            $address = $this->addresses->where('id', $this->shipping_address)->first();

            $shipping_address_json['name']        = $address->name;
            $shipping_address_json['email']       = Auth::user()->email;
            $shipping_address_json['address']     = $address->address;
            $shipping_address_json['country']     = $address->country->name;
            $shipping_address_json['state']       = $address->state->name;
            $shipping_address_json['city']        = $address->city->name;
            $shipping_address_json['postal_code'] = $address->postal_code;
            $shipping_address_json['phone']       = $address->phone;
            $shipping_address_json['longitude']   = $address->longitude;
            $shipping_address_json['latitude']    = $address->latitude;
        } else {
            $shipping_address_json['name']        =  $this->guest_address_name;
            $shipping_address_json['email']       =  $this->guest_address_email;
            $shipping_address_json['address']     =  $this->guest_address_address;
            $shipping_address_json['country']     =  $this->guest_address_country;
            $shipping_address_json['state']       =  $this->guest_address_state;
            $shipping_address_json['city']        =  $this->guest_address_city;
            $shipping_address_json['postal_code'] =  $this->guest_address_pincode;
            $shipping_address_json['phone']       =  $this->guest_address_phone;
            $shipping_address_json['longitude']   =  $this->guest_address_long;
            $shipping_address_json['latitude']    =  $this->guest_address_lat;
        }

        if ($this->diffrent_billing_address) {
            $billing_address_json['name']        = $this->billing_address_name;
            $billing_address_json['address']     = $this->billing_address_address;
            $billing_address_json['country']     = $this->billing_address_country;
            $billing_address_json['state']       = $this->billing_address_state;
            $billing_address_json['city']        = $this->billing_address_city;
            $billing_address_json['postal_code'] = $this->billing_address_pincode;
            $billing_address_json['phone']       = $this->billing_address_phone;
        } else {
            $billing_address_json = $shipping_address_json;
        }

        $shipping_address_json = json_encode($shipping_address_json);
        $billing_address_json = json_encode($billing_address_json);

        $combined_order = CombinedOrder::create([
            'user_id' => $this->user_id,
            'shipping_address' => $shipping_address_json,
            'grand_total' => $this->total,
        ]);

        $order = Order::create([
            'user_id' => Auth::check() ? $this->user_id : null,
            'guest_id' => Auth::check() ? null : $this->user_id,
            'seller_id' =>  0,
            'combined_order_id' => $combined_order->id,
            'shipping_address' => $shipping_address_json,
            'billing_address' => $billing_address_json,
            'shipping_type' => $this->shipping_method,
            'shipping_cost' => $this->shipping_rate,
            'pickup_point_id' => 0,
            'delivery_status' => 'pending',
            'payment_type' => $this->payment_method,
            'grand_total' =>  $this->total,
            'coupon_discount' => $this->coupon_rate,
            'code' => date('Ymd-His') . rand(10, 99),
            'date' => strtotime('now'),
            'delivery_viewed' => 0
        ]);

        foreach ($this->carts as $cart) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'variation' => $cart->variation,
                'og_price' => $cart->price,
                'price' => $cart->price * $cart->quantity,
                'quantity' => $cart->quantity,
            ]);
        }

        return redirect()->route('payment.checkout', [
            'order' => $order
        ]);
    }

    public function render()
    {
        $this->total = ($this->sub_total - $this->coupon_rate) + $this->shipping_rate;
        $country = $this->country_list;
        return view('livewire.frontend.checkout', [
            'country' => $country
        ])->extends('frontend.layouts.app');
    }


    // public function updating($name, $value)
    // {
    // }

    public function updatedShippingMethod($value)
    {
        $this->shipping_rate = $this->shipping_rates[$value]['rate'];
    }
}
