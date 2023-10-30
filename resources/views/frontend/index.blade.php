@extends('frontend.layouts.app')

@section('content')
    <div id="homepage-1">
        <div class="ps-home-banner ps-home-banner--1">
            <div class="ps-container">
                @if ($sliders)
                    <div class="ps-section__left">
                        <div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true"
                            data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1"
                            data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1"
                            data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn"
                            data-owl-animate-out="fadeOut">
                            @foreach ($sliders as $slider)
                                <div class="ps-banner bg--cover"
                                    data-background="{{ get_uploads_image($slider->mainImage) }}">
                                    <a class="ps-banner__overlay" href="{{ $slider->a_link }}"></a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @if ($small_banners && get_setting('home_banner_status') == 1)
                    <div class="ps-section__right">
                        @foreach ($small_banners as $small_banner)
                            <a class="ps-collection" href="{{ $small_banner->a_link }}">
                                <img loading="lazy" src="{{ get_uploads_image($small_banner->mainImage) }}"
                                    alt="" />
                            </a>
                        @endforeach
                    </div>
                @else
                    <style>
                        .ps-section__left {
                            max-width: 100% !important;
                        }
                    </style>
                @endif
            </div>
        </div>


        @if ($ads_banners && get_setting('home_banner_status') == 1)
            @php
                $img_class = 'col-xl-4 col-lg-4';
                if ($ads_banners->count() == 2) {
                    $img_class = 'col-xl-6 col-lg-6';
                } elseif ($ads_banners->count() == 1) {
                    $img_class = 'col-xl-12 col-lg-12';
                }
            @endphp

            <div class="ps-home-ads">
                <div class="ps-container">
                    <div class="row">

                        @foreach ($ads_banners as $item)
                            <div class="{{ $img_class }} col-md-12 col-sm-12 col-12">
                                <a class="ps-collection" href="{{ $item->a_link }}">
                                    <img src="{{ get_uploads_image($item->mainImage) }}" alt="" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        @if ($trending_categories)
            <div class="ps-top-categories">
                <div class="ps-container">
                    <div class="ps-section__header">
                        <h3>Trending Categories</h3>
                    </div>
                    <div class="trending-category-content">
                        <div class="trending-category">

                            @foreach ($trending_categories as $category)
                                <div class="trending-category-item">
                                    <div class="ps-block--category">
                                        <a href="{{ route('products.category', $category->slug) }}"
                                            title="{{ $category->name }}" class="ps-block__overlay">
                                            <img src="{{ uploaded_asset($category->icon) }}" alt="{{ $category->name }}">
                                            <p>{{ $category->name }}</p>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div id="large_banner_section"></div>

        @if ($newest_products)
            <div class="ps-product-list ps-clothings">
                <div class="ps-container">
                    <div class="ps-section__header">
                        <h3>Latest products</h3>
                    </div>
                    <div class="ps-section__content">
                        <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false"
                            data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="true"
                            data-owl-item="6" data-owl-item-xs="1" data-owl-item-sm="2" data-owl-item-md="3"
                            data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                            @foreach ($newest_products as $product)
                                @include('frontend.inc.product_box', $product)
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($section_categories)
            <div class="ps-top-categories">
                <div class="ps-container">
                    <div class="row">

                        @php
                            $aclass = $cat_banners ? 'col-md-9' : 'col-md-12';
                        @endphp

                        @if ($cat_banners)
                            @foreach ($cat_banners as $item)
                                <div class="col-md-3">
                                    <a href="{{ $item->a_link }}" class="ps-block__overlay">
                                        <img src="{{ get_uploads_image($item->mainImage) }}" alt="">
                                    </a>
                                </div>
                            @endforeach
                        @endif


                        <div class="{{ $aclass }}">
                            <div class="trending2-category-content p-0">
                                <div class="trending2-category">
                                    @foreach ($section_categories as $category)
                                        <div class="trending-category-item">
                                            <div class="ps-block--category">
                                                <a href="{{ route('products.category', $category->slug) }}"
                                                    title="{{ $category->name }}" class="ps-block__overlay">
                                                    <img src="{{ uploaded_asset($category->icon) }}"
                                                        alt="{{ $category->name }}">
                                                    <p>{{ $category->name }}</p>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif



        @if ($best_selling_products)
            <div class="ps-product-list ps-clothings">
                <div class="ps-container">
                    <div class="ps-section__header">
                        <h3>Best Selling</h3>
                    </div>
                    <div class="ps-section__content">
                        <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false"
                            data-owl-speed="10000" data-owl-gap="10" data-owl-nav="true" data-owl-dots="true"
                            data-owl-item="6" data-owl-item-xs="1" data-owl-item-sm="2" data-owl-item-md="3"
                            data-owl-item-lg="4" data-owl-item-xl="6" data-owl-duration="1000" data-owl-mousedrag="on">
                            @foreach ($best_selling_products as $product)
                                @include('frontend.inc.product_box', $product)
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div id="brand_section"></div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $.post('{{ route('home.section.brands') }}', {
                _token: '{{ csrf_token() }}'
            }, function(data) {
                $('#brand_section').html(data);
                owlCarouselConfig2()
            });
            @if (get_setting('home_large_banner_status') == 1)
                $.post('{{ route('home.section.large_banner') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    $('#large_banner_section').html(data);
                });
            @endif
        });
    </script>
@endsection
