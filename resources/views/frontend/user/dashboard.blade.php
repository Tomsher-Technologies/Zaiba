@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li>My Account</li>
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
                                    <div class="dashboard-home">
                                        <div class="title">
                                            <h4>My Dashboard</h4>
                                        </div>
                                        <div class="dashboard-user-name">
                                            <h6 class="text-content">Hello, <b
                                                    class="text-title">{{ auth()->user()->name }}</b></h6>
                                            <p class="text-content">From your My Account Dashboard you have the ability to
                                                view a snapshot of your recent account activity and update your account
                                                information. Select a link below to view or edit information.</p>
                                        </div>
                                        <div class="total-box">
                                            <div class="row g-sm-4 g-3">
                                                <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                                    <div class="totle-contain">
                                                        <img src="{{ frontendAsset('img/account/orders.svg') }}"
                                                            class="img-1 blur-up lazyloaded" alt="">
                                                        <img src="{{ frontendAsset('img/account/orders.svg') }}"
                                                            class="blur-up lazyloaded" alt="">
                                                        <div class="totle-detail">
                                                            <h5>Total Order</h5>
                                                            <h3>{{ $total_orders }}</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                                    <div class="totle-contain">
                                                        <img src="{{ frontendAsset('img/account/pendings.svg') }}"
                                                            class="img-1 blur-up lazyloaded" alt="">
                                                        <img src="{{ frontendAsset('img/account/pendings.svg') }}"
                                                            class="blur-up lazyloaded" alt="">
                                                        <div class="totle-detail">
                                                            <h5>Total Pending Order</h5>
                                                            <h3>{{ $pending_orders }}</h3>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                                    <div class="totle-contain">
                                                        <img src="{{ frontendAsset('img/account/wishlists.svg') }}"
                                                            class="img-1 blur-up lazyloaded" alt="">
                                                        <img src="{{ frontendAsset('img/account/wishlists.svg') }}"
                                                            class="blur-up lazyloaded" alt="">
                                                        <div class="totle-detail">
                                                            <h5>Total Wishlist</h5>
                                                            <h3>{{ wishListCount() }}</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="dashboard-title">
                                            <h3>Account Information</h3>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-xxl-12">
                                                <div class="dashboard-contant-title">
                                                    <h4>Contact Information
                                                        <a href="{{ route('profile') }}">Edit</a>
                                                    </h4>
                                                </div>
                                                <div class="dashboard-detail">
                                                    <h6 class="text-content">{{ auth()->user()->name }}</h6>
                                                    <h6 class="text-content">{{ auth()->user()->email }}</h6>
                                                    <a href="{{ route('profile.password') }}">Change Password</a>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="dashboard-contant-title">
                                                    <h4>Address Book <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#editProfile">Edit</a></h4>
                                                </div>

                                                <div class="row g-4">
                                                    <div class="col-xxl-12">
                                                        <div class="dashboard-detail">
                                                            <h6 class="text-content">Default Shipping Address</h6>

                                                            @if ($default_address)
                                                                <h6 class="text-content">
                                                                    @isset($default_address->name)
                                                                        {{ $default_address->name }} <br>
                                                                    @endisset
                                                                    @isset($default_address->address)
                                                                        {{ $default_address->address }} <br>
                                                                    @endisset
                                                                    @isset($default_address->country->name)
                                                                        {{ $default_address->country->name }} <br>
                                                                    @endisset
                                                                    @isset($default_address->city->name)
                                                                        {{ $default_address->city->name }} <br>
                                                                    @endisset
                                                                    @isset($default_address->state->name)
                                                                        {{ $default_address->state->name }} <br>
                                                                    @endisset
                                                                    @isset($default_address->postal_code)
                                                                        {{ $default_address->postal_code }} <br>
                                                                    @endisset
                                                                    @isset($default_address->phone)
                                                                        {{ $default_address->phone }} <br>
                                                                    @endisset
                                                                </h6>
                                                            @else
                                                                <h6 class="text-content">You have not set a default shipping
                                                                    address.</h6>
                                                            @endif


                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#editProfile">Edit Address</a>
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
        </div>
    </div>
@endsection
