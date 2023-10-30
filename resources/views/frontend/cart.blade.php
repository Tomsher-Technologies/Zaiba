@extends('frontend.layouts.app')

@section('content')
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
                <div class="row">
                    @if ($carts->count())
                        <div class="col-md-8">
                            <div class="table-responsive">
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
                                            @foreach ($carts as $cart)
                                                <tr id="cart-item-{{ $cart->id }}">
                                                    <td data-label="Product">
                                                        <div class="ps-product--cart">
                                                            <div class="ps-product__thumbnail">
                                                                <a href="{{ route('product', $cart->product->slug) }}"
                                                                    title="{{ $cart->product->name }}">
                                                                    <img src="{{ uploaded_asset($cart->product->thumbnail_img) }}"
                                                                        alt="{{ $cart->product->name }}"
                                                                        onerror="this.onerror=null;this.src='{{ frontendAsset('img/placeholder.webp') }}';" />
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
                                                            <button type="button" class="up quantity-plus"><i
                                                                    class="fa fa-plus"></i></button>
                                                            <button type="button" class="down quantity-minus">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <input class="form-control quantity-input"
                                                                data-min="{{ $cart->product->min_qty ?? 1 }}"
                                                                data-max="{{ $cart->product->stocks->first()->qty }}"
                                                                type="number" value="{{ $cart->quantity }}">
                                                        </div>
                                                    </td>
                                                    <td data-label="Total">
                                                        {{ format_price(home_discounted_base_price($cart->product, false) * $cart->quantity) }}
                                                    </td>
                                                    <td data-label="Actions">
                                                        <a href="#"
                                                            onclick="removeFromCart({{ $cart->id }},event)">
                                                            <i class="icon-cross"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </form>
                                    </tbody>
                                </table>
                                <button class="ps-btn" style="float: right">
                                    Update Cart
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <figure>
                                <figcaption style="font-size: 20px;">Coupon Code</figcaption>
                                <div class="form-group">
                                    <input class="form-control" type="text" placeholder="">
                                </div>
                                <div class="form-group">
                                    <button class="ps-btn ps-btn--outline">Apply</button>
                                </div>
                            </figure>
                            <div class="ps-block--shopping-total">
                                <div class="ps-block__header">
                                    <h3 class="pb-3">Order Summary</h3>
                                    <p>Sub Total (3 Items) <span> 1500.00</span></p>
                                    <p>Shipping Charge <span> AED 100.00</span></p>
                                    <p>VAT <span> AED 80.00</span></p>
                                </div>a
                                <div class="ps-block__content">
                                    <h3>Total <span>AED 1680.00</span></h3>
                                </div>
                            </div><a class="ps-btn ps-btn--fullwidth" href="checkout.html">Proceed to checkout</a>
                            <br>
                            <br>
                        </div>
                    @else
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center">
                                        <img src="{{ frontendAsset('img/cart-empty.svg') }}" alt="" class="w-50">
                                    </div>
                                    <div class="text-center mt-5 pt-1">
                                        <h4 class="mb-3 text-capitalize">Your Cart is empty
                                            !</h4>

                                        <h5 class="text-muted mb-0">What are you waiting for?</h5>
                                        <div class="mt-4 pt-2 hstack gap-2 justify-content-center">
                                            <a href="{{ route('home') }}" class="btn ps-btn btn-sm">Start Shopping </a>
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
@endsection

@section('script')
    <script>
        function removeFromCart(id, event) {
            event.preventDefault();

            $.ajax({
                type: "POST",
                url: config.routes.cart_remove,
                data: {
                    'id': id,
                    '_token': config.csrf
                },
                success: function(data, status, xhr) {
                    if (xhr.status == 200) {
                        launchToast(data.message);
                        $('.headerCartCount').html(data.count)
                        Livewire.emit('cartUpdated');
                        $('#cart-item-' + id).remove()
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 404) {
                        launchToast('Something went wrong, please try again', 'error');
                    }
                },
            });

        }
    </script>
@endsection
