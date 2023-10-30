@extends('frontend.layouts.app')

@section('content')
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body p-4 p-md-5">
                                <div class="text-center">
                                    <img src="{{ frontendAsset('img/cart-empty.svg') }}" alt="" class="w-50">
                                </div>
                                <div class="text-center mt-5 pt-1">
                                    <h4 class="mb-3 text-capitalize">Page not found</h4>
                                    <h5 class="text-muted mb-0">Oops! The page you are looking for does not exist. It might
                                        have been moved or delete.</h5>
                                    <div class="mt-4 pt-2 hstack gap-2 justify-content-center">
                                        <a href="{{ route('home') }}" class="btn ps-btn btn-sm">
											Back to home
                                        </a>
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
