@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>{{ $page->title }}</li>
            </ul>
        </div>
    </div>


    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <section class="section">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body p-4 p-md-5">
                                        <h2 class="text-center mb-4 fs-10">{{ $page->title }}</h2>
                                        {!! $page->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <style>
        .card:hover {
            box-shadow: none;
            border-color: rgba(0, 0, 0, 0.175);
        }
    </style>
@endsection
