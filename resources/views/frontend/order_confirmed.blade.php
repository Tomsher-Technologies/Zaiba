@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Order Completed</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <section class="section">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body p-4 p-md-5">
                                        <div class="text-center">
                                            <img src="{{ frontendAsset('img/sccss.png') }}" alt="" class="w-50">
                                        </div>
                                        <div class="text-center mt-5 pt-1">
                                            <h4 class="mb-3 text-capitalize">Your Order Is Completed !</h4>
                                            <p class="text-muted mb-2">You will receive an order confirmation email with
                                                details of your order.</p>
                                            <p class="text-muted mb-0">Order ID: {{ $order->code }}</p>
                                            <div class="mt-4 pt-2 hstack gap-2 justify-content-center">
                                                @auth
                                                    <a href="{{ route('purchase_history.index') }}"
                                                        class="btn ps-btn btn-sm">View Order </a>
                                                @endauth
                                                <a href="{{ route('home') }}" class="btn btn-success-add-ad btn-sm">Back To
                                                    Home </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
