<div>
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Cart</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row justify-content-center">
                    @if ($carts->count())
                        <div class="col-md-8">
                            <div class="table-responsive position-relative">

                                <div class="position-absolute h-100 w-100 start-0 top-0 z-1020" style="z-index: 999"
                                    wire:loading>
                                    <img class="h-100 w-100" style="object-fit: cover;opacity: .6"
                                        src="{{ frontendAsset('img/Loading_icon.gif') }}" alt="Loading">
                                </div>

                                <table class="table ps-table--shopping-cart ps-table--responsive">
                                    <thead class="ps-table--shopping-cart-header">
                                        <tr>
                                            <th>Product Details</th>
                                            <th>PRICE</th>
                                            <th>QUANTITY</th>
                                            <th>TOTAL</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <form action="">
                                            @foreach ($carts as $index => $cart)
                                                @php
                                                    $row_total = $cart->price * $cart->quantity;
                                                    $item_total += $row_total;
                                                    if ($cart->coupon_applied) {
                                                        $coupon_total += $cart->discount;
                                                    }
                                                @endphp
                                                <tr id="cart-item-{{ $cart->id }}">
                                                    <td data-label="Product">
                                                        <div class="ps-product--cart">
                                                            <div class="ps-product__thumbnail">
                                                                <a href="{{ route('product', $cart->product->slug) }}"
                                                                    title="{{ $cart->product->name }}">
                                                                    <img src="{{ uploaded_asset($cart->product->thumbnail_img) ?? frontendAsset('img/placeholder.webp') }}"
                                                                        alt="{{ $cart->product->name }}" />
                                                                </a>
                                                            </div>
                                                            <div class="ps-product__content">
                                                                <a href="{{ route('product', $cart->product->slug) }}">
                                                                    {{ $cart->product->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="price" data-label="Price">
                                                        {{ home_discounted_base_price($cart->product) }}
                                                    </td>
                                                    <td data-label="Quantity">
                                                        <div class="form-group--number">
                                                            <button wire:click.prevent="increment({{ $cart->id }})"
                                                                type="button" class="up quantity-plus">
                                                                <i class="fa fa-plus"></i></button>
                                                            <button wire:click.prevent="decrement({{ $cart->id }})"
                                                                type="button" class="down quantity-minus">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <input class="form-control quantity-input"
                                                                wire:change="updateQuantity({{ $cart->id }}, $event.target.value)"
                                                                data-min="{{ $cart->product->min_qty ?? 1 }}"
                                                                data-max="{{ $cart->product->stocks->first()->qty }}"
                                                                type="number" value="{{ $cart->quantity }}" />
                                                        </div>
                                                    </td>
                                                    <td data-label="Total">
                                                        {{ format_price(convert_price($row_total)) }}
                                                    </td>
                                                    <td data-label="Actions">
                                                        <a href="#"
                                                            wire:click.prevent="remove({{ $cart->id }})">
                                                            <i class="icon-cross"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <figure class="position-relative">
                                <div class="position-absolute h-100 w-100 start-0 top-0 z-1020" style="z-index: 999"
                                    wire:loading>
                                    <img class="h-100 w-100" style="object-fit: cover;opacity: .6"
                                        src="{{ frontendAsset('img/Loading_icon.gif') }}" alt="Loading">
                                </div>

                                <figcaption style="font-size: 20px;">Coupon Code</figcaption>
                                @if ($coupon_added)
                                    <span>
                                        {{ $this->coupon_code }}
                                        <a href="#" wire:click.prevent="removeCoupon()">
                                            <i class="icon-cross"></i>
                                        </a>
                                    </span>
                                    <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                        <strong>Coupon applied succesfully</strong>
                                    </span>
                                @else
                                    <form wire:submit.prevent="applyCoupon()">

                                        <div class="form-group mb-0">
                                            <input wire:model.defer="coupon_code" class="form-control text-uppercase"
                                                type="text" placeholder="">
                                        </div>
                                        @error('coupon_code')
                                            <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="form-group mt-4">
                                            <button type="submit" class="ps-btn ps-btn--outline">Apply</button>
                                        </div>
                                    </form>
                                @endif


                            </figure>

                            @php
                                $cart_total = $item_total - $coupon_total;
                            @endphp

                            <div class="ps-block--shopping-total position-relative">

                                <div class="position-absolute h-100 w-100 start-0 top-0 z-1020" style="z-index: 999"
                                    wire:loading>
                                    <img class="h-100 w-100" style="object-fit: cover;opacity: .6"
                                        src="{{ frontendAsset('img/Loading_icon.gif') }}" alt="Loading">
                                </div>

                                <div class="ps-block__header">
                                    <h3 class="pb-3">Order Summary</h3>
                                    <p>Sub Total ({{ $carts->count() }} {{ Str::plural('Item', $carts->count()) }})
                                        <span> {{ format_price(convert_price($item_total)) }}</span>
                                    </p>
                                    @if ($coupon_total > 0)
                                        <p>Coupon discount
                                            <span>{{ format_price(convert_price($coupon_total)) }}</span></p>
                                    @endif
                                </div>
                                <div class="ps-block__content">
                                    <h3>Total <span>{{ format_price(convert_price($cart_total)) }}</span></h3>
                                </div>
                            </div>
                            <a wire:loading.attr="disabled" class="ps-btn ps-btn--fullwidth"
                                href="{{ route('checkout.checkout_page') }}">Proceed to checkout</a>
                        </div>
                    @else
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center">
                                        <img src="{{ frontendAsset('img/cart-empty.svg') }}" alt=""
                                            class="w-50">
                                    </div>
                                    <div class="text-center mt-5 pt-1">
                                        <h4 class="mb-3 text-capitalize">Your cart is empty
                                            !</h4>

                                        <h5 class="text-muted mb-0">What are you waiting for?</h5>
                                        <div class="mt-4 pt-2 hstack gap-2 justify-content-center">
                                            <a href="{{ route('home') }}" class="btn ps-btn btn-sm">Start Shopping
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
