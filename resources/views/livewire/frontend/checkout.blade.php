<div>
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Checkout</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">

            @if ($carts->count())
                <div id="checkout-steps" class="step-checkout">
                    <ul class="step-checkout_list" id="checkoutList">
                        <!-- Checkout items/tabs -->
                        <li role="button" class="step-checkout_item {{ $current_step == 1 ? 'active' : '' }}"
                            wire:click.prevent="step1()" id="stepCheckoutItem1" tabindex="1">
                            <span>Shipping Address</span>
                        </li>
                        <li role="button" class="step-checkout_item {{ $current_step == 2 ? 'active' : '' }}"
                            wire:click.prevent="step2()" id="stepCheckoutItem2" tabindex="1">
                            <span> Billing Address</span>
                        </li>
                        <li role="button" class="step-checkout_item {{ $current_step == 3 ? 'active' : '' }}"
                            id="stepCheckoutItem3" wire:click.prevent="step3()" tabindex="1">
                            <span>SHIPPING </span>
                        </li>
                        <li role="button" class="step-checkout_item {{ $current_step == 4 ? 'active' : '' }}"
                            id="stepCheckoutItem4" wire:click.prevent="step4()" tabindex="1">
                            <span>Payment</span>
                        </li>
                        <li class="step-checkout_content">
                            <!-- Split Checkout steps from the Summary -->
                            <div class="grid-x">
                                <!-- Checkout step content here -->
                                <div class="col">
                                    <div class="step-checkout_item_content" id="stepCheckoutContent1">
                                        <div id="checkout-steps" class="checkout-steps-accordion">
                                            <div class="step-box">
                                                <h2>Shipping Address</h2>
                                                @auth

                                                    <div class="row g-4 position-relative" id="address-list">
                                                        @if ($addresses && $addresses->count())
                                                            @foreach ($addresses as $address)
                                                                <div class="col-lg-6">
                                                                    <div class="ship-address-box">
                                                                        <div class="form-check card-radio">
                                                                            <input wire:model="shipping_address"
                                                                                id="shippingAddress{{ $address->id }}"
                                                                                name="shippingAddress" type="radio"
                                                                                value="{{ $address->id }}"
                                                                                class="form-check-input">
                                                                            <label class="form-check-label"
                                                                                for="shippingAddress{{ $address->id }}">
                                                                                <span
                                                                                    class="mb-4 fw-semibold fs-12 d-block text-muted text-uppercase">
                                                                                    {{ $address->name ?? auth()->user()->name }}
                                                                                </span>
                                                                                <span
                                                                                    class="text-muted fw-normal text-wrap mb-1 d-block">
                                                                                    {{ $address->address }}, <br>
                                                                                    {{ $address->postal_code }}, <br>
                                                                                    {{ $address->city->name }}, <br>
                                                                                    {{ $address->state->name }}, <br>
                                                                                    {{ $address->country->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-normal d-block">
                                                                                    {{ $address->phone }}
                                                                                </span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif


                                                        <div class="col-lg-6">
                                                            <div
                                                                class="text-center p-4 rounded-3 border border-2 border-dashed">
                                                                <div class="avatar-md mx-auto mb-4">
                                                                    <div
                                                                        class="avatar-title bg-success-subtle text-success rounded-circle display-6">
                                                                        <i class="fa fa-map-pin"></i>
                                                                    </div>
                                                                </div>
                                                                <h5 class="fs-16 mb-3">Add New Address</h5>
                                                                <button {{ $btn_disabled ? 'disabled' : '' }}
                                                                    type="button"
                                                                    class="btn btn-success-add-ad btn-sm  addAddress-modal"
                                                                    id="addAddressContaniner"
                                                                    onclick="add_new_address()">Add</button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group mb-1">
                                                                <div class="ps-checkbox">
                                                                    <input wire:model="diffrent_billing_address"
                                                                        class="form-control" type="checkbox" id="cb01">
                                                                    <label for="cb01">Use a different billing
                                                                        address?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @error('shipping_address')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                @else
                                                    <div class="ps-form__billing-info">
                                                        <div class="row">

                                                            <div class="col-md-12 mb-3">
                                                                <div wire:ignore class=" row">
                                                                    <label class="col-md-2">Location</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control"
                                                                            id="us4-address" />
                                                                    </div>
                                                                    <div class="col-sm-12 mt-3">
                                                                        <div id="us4" style="height: 400px;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" wire:model="guest_address_lat"
                                                                name="latitude" class="form-control" id="us4-lat" />
                                                            <input type="hidden" wire:model="guest_address_long"
                                                                name="longitude" class="form-control" id="us4-lon" />

                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Name<sup>*</sup>
                                                                    </label>
                                                                    <div class="form-group__content">
                                                                        <input wire:model.lazy='guest_address_name'
                                                                            class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_name')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email<sup>*</sup>
                                                                    </label>
                                                                    <div class="form-group__content">
                                                                        <input wire:model.lazy='guest_address_email'
                                                                            class="form-control" type="email">
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_email')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Phone<sup>*</sup>
                                                                    </label>
                                                                    <div class="form-group__content">
                                                                        <input wire:model.lazy='guest_address_phone'
                                                                            class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_phone')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>



                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Address<sup>*</sup>
                                                                    </label>
                                                                    <div class="form-group__content">
                                                                        <textarea wire:model.lazy='guest_address_address' class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_address')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Country<sup>*</sup>
                                                                        </label>
                                                                        <div class="form-group__content" wire:ignore>
                                                                            <select wire:model="guest_address_country"
                                                                                class="form-control aiz-selectpicker"
                                                                                data-live-search="true"
                                                                                data-placeholder="Select your country"
                                                                                name="country_id" required>
                                                                                <option value="">Select your country
                                                                                </option>
                                                                                @if ($country)
                                                                                    @foreach ($country as $key => $coun)
                                                                                        <option
                                                                                            value="{{ $coun->id }}">
                                                                                            {{ $coun->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @error('guest_address_country')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>State<sup>*</sup>
                                                                        </label>
                                                                        <div class="form-group__content" wire:ignore>
                                                                            <select wire:model="guest_address_state"
                                                                                class="form-control mb-3 aiz-selectpicker"
                                                                                data-live-search="true" name="state_id"
                                                                                required>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    @error('guest_address_state')
                                                                        <div class="alert alert-danger">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>City<sup>*</sup>
                                                                    </label>
                                                                    <div class="form-group__content" wire:ignore>
                                                                        <select wire:model="guest_address_city"
                                                                            class="form-control mb-3 aiz-selectpicker"
                                                                            data-live-search="true" name="city_id"
                                                                            required>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_city')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Pin Code
                                                                    </label>
                                                                    <div class="form-group__content">
                                                                        <input wire:model.lazy='guest_address_pincode'
                                                                            class="form-control" type="text">
                                                                    </div>
                                                                </div>
                                                                @error('guest_address_pincode')
                                                                    <div class="alert alert-danger">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>


                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group mb-1">
                                                                <div class="ps-checkbox">
                                                                    <input wire:model="diffrent_billing_address"
                                                                        class="form-control" type="checkbox"
                                                                        id="cb01">
                                                                    <label for="cb01">Use a different billing
                                                                        address?</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endauth

                                                <div class="mt-n5 d-flex gap-3 flex-wrap align-items-end pt-4">
                                                    <div class="ms-md-auto">
                                                        <button wire:loading.attr="disabled"
                                                            wire:click.prevent="step2()"
                                                            class="ps-btn action-btn">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step-checkout_item_content" id="stepCheckoutContent2">
                                        <div class="step-box position-relative">

                                            <h2>Billing Address</h2>

                                            <div class="ps-form__billing-info">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Name<sup>*</sup>
                                                            </label>
                                                            <div class="form-group__content">
                                                                <input wire:model.lazy='billing_address_name'
                                                                    class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                        @error('billing_address_name')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Address<sup>*</sup>
                                                            </label>
                                                            <div class="form-group__content">
                                                                <textarea wire:model.lazy='billing_address_address' class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                        @error('billing_address_address')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Country<sup>*</sup>
                                                                </label>
                                                                <div class="form-group__content" wire:ignore>
                                                                    <select wire:model="billing_address_country"
                                                                        class="form-control aiz-selectpicker"
                                                                        data-live-search="true"
                                                                        data-placeholder="Select your country"
                                                                        name="country_id" required>
                                                                        <option value="">Select your country
                                                                        </option>
                                                                        @if ($country)
                                                                            @foreach ($country as $key => $coun)
                                                                                <option value="{{ $coun->id }}">
                                                                                    {{ $coun->name }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @error('billing_address_country')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>State<sup>*</sup>
                                                                </label>
                                                                <div class="form-group__content" wire:ignore>
                                                                    <select wire:model="billing_address_state"
                                                                        class="form-control mb-3 aiz-selectpicker"
                                                                        data-live-search="true" name="state_id"
                                                                        required>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            @error('billing_address_state')
                                                                <div class="alert alert-danger">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>City<sup>*</sup>
                                                            </label>
                                                            <div class="form-group__content" wire:ignore>
                                                                <select wire:model="billing_address_city"
                                                                    class="form-control mb-3 aiz-selectpicker"
                                                                    data-live-search="true" name="city_id" required>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @error('billing_address_city')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Pin Code
                                                            </label>
                                                            <div class="form-group__content">
                                                                <input wire:model.lazy='billing_address_pincode'
                                                                    class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                        @error('billing_address_pincode')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Phone<sup>*</sup>
                                                            </label>
                                                            <div class="form-group__content">
                                                                <input wire:model.lazy='billing_address_phone'
                                                                    class="form-control" type="text">
                                                            </div>
                                                        </div>
                                                        @error('billing_address_phone')
                                                            <div class="alert alert-danger">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>


                                                </div>
                                            </div>

                                            <div class="mt-n5 d-flex gap-3 flex-wrap align-items-end pt-4">
                                                <div class="ms-md-auto">
                                                    @if ($btn_disabled)
                                                        <img class="loadingImage"
                                                            src="{{ frontendAsset('img/Loading_icon.gif') }}"
                                                            alt="Loading">
                                                    @endif

                                                    <button wire:loading.attr="disabled" wire:click.prevent="step1()"
                                                        class="ps-btn ps-btn--black action-btn">Back</button>
                                                    <button wire:loading.attr="disabled" wire:click.prevent="step3()"
                                                        class="ps-btn ">Next</button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="step-checkout_item_content" id="stepCheckoutContent3">
                                        <div class="step-box">
                                            <h2>Shipping</h2>
                                            <div class="shipping-charge">

                                                @foreach ($shipping_rates as $key => $shp_rate)
                                                    {{-- @dd($shp_rate) --}}
                                                    <label class="card shipping-charge-box">
                                                        <input wire:model="shipping_method" name="plan"
                                                            value="{{ $key }}" class="radio"
                                                            type="radio" {{ $loop->index == 0 ? 'checked' : '' }}>
                                                        <span class="plan-details">
                                                            <span class="plan-type">{{ $shp_rate['name'] }}</span>
                                                            @if (isset($shp_rate['rate']))
                                                                <span
                                                                    class="plan-cost">{{ format_price(convert_price($shp_rate['rate'])) }}</span>
                                                            @endif
                                                            @if (isset($shp_rate['note']))
                                                                <span class="plan-note">
                                                                    {!! $shp_rate['note'] !!}
                                                                </span>
                                                            @endif

                                                        </span>
                                                    </label>
                                                @endforeach

                                                @error('shipping_method')
                                                    <div class="alert alert-danger">
                                                        {{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="mt-n5 d-flex gap-3 flex-wrap align-items-end pt-4">
                                                <div class="ms-md-auto">
                                                    <button wire:loading.attr="disabled"
                                                        wire:click.prevent="{{ $diffrent_billing_address ? 'step2()' : 'step1()' }}"
                                                        class="ps-btn ps-btn--black action-btn">Back</button>
                                                    <button wire:loading.attr="disabled" wire:click.prevent="step4()"
                                                        class="ps-btn ">Next</button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="step-checkout_item_content" id="stepCheckoutContent4">
                                        <div class="step-box">
                                            <h2>PAYMENT</h2>
                                            <div class="payment-methods">
                                                <ul class="payment-methods__list">
                                                    @foreach ($payment_methods as $payment_met)
                                                        <li
                                                            class="payment-methods__item payment-methods__item{{ $payment_method == $payment_met['type'] ? '--active' : '' }}">
                                                            <label class="payment-methods__item-header">
                                                                <span class="payment-methods__item-radio input-radio">
                                                                    <span class="input-radio__body">
                                                                        <input wire:model='payment_method'
                                                                            class="input-radio__input"
                                                                            name="checkout_payment_method"
                                                                            value="{{ $payment_met['type'] }}"
                                                                            type="radio" checked="checked">
                                                                        <span class="input-radio__circle"></span>
                                                                    </span>
                                                                </span>
                                                                <span class="payment-methods__item-title">
                                                                    {{ $payment_met['name'] }}
                                                                </span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>


                                            <div class="mt-n5 d-flex gap-3 flex-wrap align-items-end pt-4">
                                                <div class="w-100">
                                                    <button wire:loading.attr="disabled" wire:click.prevent="step3()"
                                                        class="ps-btn ps-btn--black action-btn">Back</button>

                                                    <button wire:loading.attr="disabled"
                                                        wire:click.prevent="checkout()"
                                                        class="ps-btn action-btn float-end">Proceed to Payment</button>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- Summary content here -->
                                <div class="col">
                                    <div class="step-checkout_summary">
                                        <div class="summary-box">
                                            <div class="ps-form__total">
                                                <h3 class="ps-form__heading">Order Details</h3>
                                                <div class="content">
                                                    <div class="ps-block--checkout-total">
                                                        <div class="ps-block__header">
                                                            <p>Product</p>
                                                            <p>Total</p>
                                                        </div>
                                                        <div class="ps-block__content">
                                                            <table class="table ps-block__products">
                                                                <tbody>

                                                                    @foreach ($carts as $cart)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $cart->product->name }} x
                                                                                {{ $cart->quantity }}
                                                                            </td>
                                                                            <td class="text-end">
                                                                                {{ format_price(convert_price($cart->quantity * $cart->price)) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>


                                                            <h4 class="ps-block__title">Subtotal:
                                                                <span>
                                                                    {{ format_price(convert_price($sub_total)) }}</span>
                                                            </h4>


                                                            @if ($copupn_applied)
                                                                <h4 class="ps-block__title">
                                                                    Coupon:
                                                                    <span>
                                                                        -{{ format_price(convert_price($coupon_rate)) }}
                                                                    </span>
                                                                </h4>
                                                            @endif

                                                            @if ($shipping_rate)
                                                                <h4 class="ps-block__title">Shipping Charge:
                                                                    <span>
                                                                        {{ format_price(convert_price($shipping_rate)) }}
                                                                    </span>
                                                                </h4>
                                                            @endif

                                                            <h3>
                                                                Total:
                                                                <span>
                                                                    {{ format_price(convert_price($total)) }}
                                                                </span>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            @else
                <p>
                    You dont have products in cart.
                </p>
            @endif

        </div>
    </div>


    <div wire:ignore.self class="modal fade" id="new-address-modal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Address</h5>
                    <button type="button" class="ps-btn--close  close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="saveAddress()" class="form-default" role="form" id="addressAddForm">
                    <div class="modal-body">
                        <div class="p-3">
                            <div wire:ignore class=" row">
                                <label class="col-md-2">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="us3-address" />
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div id="us3" style="height: 400px;"></div>
                                </div>
                            </div>

                            <input type="hidden" wire:model="new_address_lat" name="latitude" class="form-control"
                                id="us3-lat" />
                            <input type="hidden" wire:model="new_address_lat" name="longitude" class="form-control"
                                id="us3-lon" />

                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="Your Name"
                                        name="name" wire:model.lazy="new_address_name"
                                        value="{{ auth()->user() ? auth()->user()->name : '' }}" required>
                                    @error('new_address_name')
                                        <div class="alert alert-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Address</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea wire:model.lazy="new_address_address" class="form-control mb-3" placeholder="Your Address" rows="2"
                                        name="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Country</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3" wire:ignore>
                                        <select wire:model="new_address_country" wire:change="countryChange"
                                            class="form-control aiz-selectpicker" data-live-search="true"
                                            data-placeholder="Select your country" name="country_id" required>
                                            <option value="">Select your country</option>
                                            @if ($country)
                                                @foreach ($country as $key => $coun)
                                                    <option value="{{ $coun->id }}">{{ $coun->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>State</label>
                                </div>
                                <div class="col-md-10" wire:ignore>
                                    <select wire:model="new_address_state" class="form-control mb-3 aiz-selectpicker"
                                        data-live-search="true" name="state_id" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>City</label>
                                </div>
                                <div class="col-md-10" wire:ignore>
                                    <select wire:model="new_address_city" class="form-control mb-3 aiz-selectpicker"
                                        data-live-search="true" name="city_id" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Postal code</label>
                                </div>
                                <div class="col-md-10">
                                    <input wire:model.lazy="new_address_pincode" type="text"
                                        class="form-control mb-3 numbers-only" placeholder="Your Postal Code"
                                        name="postal_code" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Phone</label>
                                </div>
                                <div class="col-md-10">
                                    <input wire:model.lazy="new_address_phone" type="text"
                                        class="form-control mb-3 numbers-only" placeholder="+971" name="phone"
                                        value="" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="ps-btn ps-btn--fullwidth">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @section('script')
        <script>
            // Add active class to the current list tem (highlight it)

            // var checkoutList = document.getElementById("checkoutList");
            // var checkoutItems = checkoutList.getElementsByClassName("step-checkout_item");
            // for (var i = 0; i < checkoutItems.length; i++) {
            //     checkoutItems[i].addEventListener("click", function() {

            //         var current = checkoutList.getElementsByClassName("active");
            //         current[0].className = current[0].className.replace(" active", "");
            //         this.className += " active";
            //     });
            // }

            /*
              // Checkout payment methods
              */
            $(function() {
                $('[name="checkout_payment_method"]').on('change', function() {
                    const currentItem = $(this).closest('.payment-methods__item');

                    $(this).closest('.payment-methods__list').find('.payment-methods__item').each(function(i,
                        element) {
                        const links = $(element);
                        const linksContent = links.find('.payment-methods__item-container');

                        if (element !== currentItem[0]) {
                            const startHeight = linksContent.height();

                            linksContent.css('height', startHeight + 'px');
                            links.removeClass('payment-methods__item--active');
                            linksContent.height(); // force reflow

                            linksContent.css('height', '');
                        } else {
                            const startHeight = linksContent.height();

                            links.addClass('payment-methods__item--active');

                            const endHeight = linksContent.height();

                            linksContent.css('height', startHeight + 'px');
                            linksContent.height(); // force reflow
                            linksContent.css('height', endHeight + 'px');
                        }
                    });
                });

                $('.payment-methods__item-container').on('transitionend', function(event) {
                    if (event.originalEvent.propertyName === 'height') {
                        $(this).css('height', '');
                    }
                });
            });
        </script>

        <script>
            window.addEventListener('addressAdded', event => {
                $('#new-address-modal').modal('hide');
                launchToast('Address added');
            })

            // window.addEventListener('showStep', event => {
            //     var checkoutList = document.getElementById("checkoutList");
            //     var checkoutItems = checkoutList.getElementsByClassName("step-checkout_item");
            //     var current = checkoutList.getElementsByClassName("active");
            //     current[0].className = current[0].className.replace(" active", "");
            //     checkoutItems[event.detail - 1].className += " active";
            // })

            function add_new_address() {
                $('#new-address-modal').modal('show');
            }

            $(document).on('change', '[name=country_id]', function() {
                var country_id = $(this).val();
                get_states(country_id);
            });

            $(document).on('change', '[name=state_id]', function() {
                var state_id = $(this).val();
                get_city(state_id);
            });

            function get_states(country_id) {
                $('[name="state"]').html("");
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('get-state') }}",
                    type: 'POST',
                    data: {
                        country_id: country_id
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj != '') {
                            $('[name="state_id"]').html(obj);
                        }
                    }
                });
            }

            function get_city(state_id) {
                $('[name="city"]').html("");
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('get-city') }}",
                    type: 'POST',
                    data: {
                        state_id: state_id
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj != '') {
                            $('[name="city_id"]').html(obj);
                        }
                    }
                });
            }
        </script>


        <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&v=weekly"></script>
        <script src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>

        @auth
            <script>
                function showPosition(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    @this.set('new_address_lat', lat);
                    @this.set('new_address_long', lng);
                    loadMap(lat, lng)
                }

                function showPositionerror() {
                    loadMap(25.2048, 55.2708)
                }

                function loadMap(lat, lng) {
                    $('#us3').locationpicker({
                        location: {
                            latitude: lat,
                            longitude: lng
                        },
                        radius: 0,
                        inputBinding: {
                            latitudeInput: $('#us3-lat'),
                            longitudeInput: $('#us3-lon'),
                            radiusInput: $('#us3-radius'),
                            locationNameInput: $('#us3-address')
                        },
                        enableAutocomplete: true,
                        onchanged: function(currentLocation, radius, isMarkerDropped) {
                            @this.set('new_address_lat', currentLocation.latitude);
                            @this.set('new_address_long', currentLocation.longitude);
                        }
                    });
                }

                $(document).ready(function() {
                    @this.set('new_address_lat', 25.2048);
                    @this.set('new_address_long', 55.2708);
                    if (navigator.geolocation) {
                        navigator.geolocation.watchPosition(showPosition, showPositionerror);
                    } else {
                        loadMap(25.2048, 55.2708)
                    }
                });
            </script>
        @else
            <script>
                function showPosition(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    @this.set('guest_address_lat', lat);
                    @this.set('guest_address_long', lng);
                    loadMap2(lat, lng)
                }

                function showPositionerror() {
                    loadMap2(25.2048, 55.2708)
                }

                function loadMap2(lat, lng) {
                    $('#us4').locationpicker({
                        location: {
                            latitude: lat,
                            longitude: lng
                        },
                        radius: 0,
                        inputBinding: {
                            latitudeInput: $('#us4-lat'),
                            longitudeInput: $('#us4-lon'),
                            radiusInput: $('#us4-radius'),
                            locationNameInput: $('#us4-address')
                        },
                        enableAutocomplete: true,
                        onchanged: function(currentLocation, radius, isMarkerDropped) {
                            @this.set('guest_address_lat', currentLocation.latitude);
                            @this.set('guest_address_long', currentLocation.longitude);
                        }
                    });
                }

                $(document).ready(function() {
                    @this.set('guest_address_lat', 25.2048);
                    @this.set('guest_address_long', 55.2708);
                    if (navigator.geolocation) {
                        navigator.geolocation.watchPosition(showPosition, showPositionerror);
                    } else {
                        loadMap2(25.2048, 55.2708)
                    }
                });
            </script>
        @endauth
    @endsection
    @section('header')
        <style>
            .accordion-button {
                font-size: inherit;
            }

            .addressLabel.checked .border {
                border-color: #eb6228 !important;
                box-shadow: 0px 0px 5px 0px #eb6228;
            }

            .c-poniter,
            .addressLabel:hover {
                cursor: pointer;
            }

            .modal {
                --bs-modal-width: 630px;
            }

            .pac-container {
                z-index: 99999;
            }

            .loadingImage {
                height: 60px;
                width: 60px;
                object-fit: cover;
            }

            .action-btn {
                background-color: #FF7F00;
                border: none;
                font-size: 20px;
                font-weight: 600;
                text-transform: uppercase;
                padding: 0.5em 1.25em;
                color: white;
                border-radius: 0.15em;
                transition: 0.3s;
                cursor: pointer;
                position: relative;
                display: block;
            }

            .action-btn:hover {
                background-color: #eb6228;
            }

            .action-btn:focus {
                outline: 0.05em dashed #eb6228;
                outline-offset: 0.05em;
            }

            .action-btn::after {
                content: '';
                display: block;
                width: 1.2em;
                height: 1.2em;
                position: absolute;
                left: calc(50% - 0.75em);
                top: calc(50% - 0.75em);
                border: 0.15em solid transparent;
                border-right-color: white;
                border-radius: 50%;
                animation: button-anim 0.7s linear infinite;
                opacity: 0;
            }

            @keyframes button-anim {
                from {
                    transform: rotate(0);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .action-btn.loading {
                color: transparent;
            }

            .action-btn.loading::after {
                opacity: 1;
            }

            /* em values are used to adjust button values such as padding, radius etc. based on font-size */

            .action-btn:disabled {
                color: #eb6228
            }

            .action-btn:disabled::after {
                opacity: 1;
            }
        </style>
    @endsection

</div>
