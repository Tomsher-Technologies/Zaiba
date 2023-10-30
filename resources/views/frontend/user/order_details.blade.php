@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">My Account</a></li>
                <li>My Orders History</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row">
                    @include('frontend.partials.dashboard.sidebar')
                    <div class="col-xxl-8 col-lg-8">
                        <div class="dashboard-right-sidebar">
                            <div class="tab-content">
                                <div class="">
                                    <div class="card">
                                        <div class="order-header">
                                            <div class="order-header__actions">
                                                <a href="{{ route('purchase_history.index') }}" class="ps-btn medium">Back
                                                    to list</a>
                                            </div>
                                            <h5 class="order-header__title">Order #{{ $order->code }}</h5>
                                            <div class="order-header__subtitle">
                                                Was placed on <mark>{{ $order->created_at->format('d F, Y') }}</mark> and is
                                                currently
                                                <mark>{{ getDeliveryStatusText($order->delivery_status) }}</mark>.
                                            </div>
                                        </div>
                                        <div class="card-divider"></div>
                                        <div class="card-table">
                                            <div class="table-responsive-sm">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Sub Total</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="card-table__body card-table__body--merge-rows">

                                                        @foreach ($order->orderDetails as $item)
                                                            <tr>
                                                                <td>{{ $item->product->name }}</td>
                                                                <td>{{ format_price($item->price) }}</td>
                                                                <td>{{ format_price($item->price * $item->quantity) }}</td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                    <tbody class="card-table__body card-table__body--merge-rows">
                                                        <tr>
                                                            <th>Subtotal</th>
                                                            <td></td>
                                                            <td>{{ format_price($order->grand_total) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Shipping</th>
                                                            <td></td>
                                                            <td>$25.00</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Tax</th>
                                                            <td></td>
                                                            <td>$262.00</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Total</th>
                                                            <td></td>
                                                            <td>$1596.00</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 no-gutters mx-n2">
                                        <div class="col-sm-6 col-12 px-2">
                                            <div class="card address-card address-card--featured">
                                                <div class="address-card__badge tag-badge tag-badge--theme">Shipping
                                                    Address</div>
                                                <div class="address-card__body">
                                                    @php
                                                        $addresss = json_decode($order->shipping_address);
                                                    @endphp
                                                    @isset($addresss->name)
                                                        <div class="address-card__name">{{ $addresss->name }}</div>
                                                    @endisset
                                                    @isset($addresss->address)
                                                        <div class="address-card__row">
                                                            {{ $addresss->address }} <br>
                                                            @isset($addresss->city)
                                                                {{ $addresss->city }} <br>
                                                            @endisset
                                                            @isset($addresss->state)
                                                                {{ $addresss->state }} <br>
                                                            @endisset
                                                            @isset($addresss->country)
                                                                {{ $addresss->country }} <br>
                                                            @endisset
                                                            @isset($addresss->postal_code)
                                                                {{ $addresss->postal_code }}
                                                            @endisset
                                                        </div>
                                                    @endisset
                                                    @isset($addresss->phone)
                                                        <div class="address-card__row">
                                                            <div class="address-card__row-title">Phone Number</div>
                                                            <div class="address-card__row-content">
                                                                {{ $addresss->phone }}
                                                            </div>
                                                        </div>
                                                    @endisset
                                                    @isset($addresss->email)
                                                        <div class="address-card__row">
                                                            <div class="address-card__row-title">Email Address</div>
                                                            <div class="address-card__row-content">
                                                                {{ $addresss->email }}
                                                            </div>
                                                        </div>
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-12 px-2 mt-sm-0 mt-3">
                                            <div class="card address-card address-card--featured">
                                                <div class="address-card__badge tag-badge tag-badge--theme">Billing
                                                    Address</div>
                                                <div class="address-card__body">
                                                    

                                                    @php
                                                    $addresss = json_decode($order->shipping_address);
                                                @endphp
                                                @isset($addresss->name)
                                                    <div class="address-card__name">{{ $addresss->name }}</div>
                                                @endisset
                                                @isset($addresss->address)
                                                    <div class="address-card__row">
                                                        {{ $addresss->address }} <br>
                                                        @isset($addresss->city)
                                                            {{ $addresss->city }} <br>
                                                        @endisset
                                                        @isset($addresss->state)
                                                            {{ $addresss->state }} <br>
                                                        @endisset
                                                        @isset($addresss->country)
                                                            {{ $addresss->country }} <br>
                                                        @endisset
                                                        @isset($addresss->postal_code)
                                                            {{ $addresss->postal_code }}
                                                        @endisset
                                                    </div>
                                                @endisset
                                                @isset($addresss->phone)
                                                    <div class="address-card__row">
                                                        <div class="address-card__row-title">Phone Number</div>
                                                        <div class="address-card__row-content">
                                                            {{ $addresss->phone }}
                                                        </div>
                                                    </div>
                                                @endisset
                                                @isset($addresss->email)
                                                    <div class="address-card__row">
                                                        <div class="address-card__row-title">Email Address</div>
                                                        <div class="address-card__row-content">
                                                            {{ $addresss->email }}
                                                        </div>
                                                    </div>
                                                @endisset

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
