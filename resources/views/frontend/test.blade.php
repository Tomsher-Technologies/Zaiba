@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Checkout</li>
            </ul>
        </div>
    </div>
    <div wire:ignore class="ps-section--shopping ps-shopping-cart">
        <div class="container">





        </div>
    </div>
@endsection

@section('script')
@endsection
