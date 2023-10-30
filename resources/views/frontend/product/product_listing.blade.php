@extends('frontend.layouts.app')

@section('meta')
    @if ($category_id)
        <meta name="category_id" content="{{ $category_id }}" />
    @endif
    @if ($brand_id)
        <meta name="brand_id" content="{{ $brand_id }}" />
    @endif

    <!-- Schema.org markup for Google+ -->
    {{-- <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:price:currency" content="{{ \App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}"> --}}
@endsection

@section('content')
    {{-- <div class="ps-breadcrumb">
        <div class="ps-container">
            {{ Breadcrumbs::render('category_byid', $category_id) }}
        </div>
    </div> --}}

    <div class="ps-page--shop">
        <div class="ps-container">
            <div class="ps-layout--shop">
                
                @include('frontend.product.listing_sidebar')

                <div class="ps-layout__right">
                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-shopping__header">
                            <p>Showing <strong> {{ $products->firstItem() }} to {{ $products->lastItem() }}
                                of total {{$products->total()}} </strong>products </p>
                            <div class="ps-shopping__actions">
                                <select name="sort_by" class="ps-select sort_by_select" data-placeholder="Sort Items">
                                    <option {{ $sort_by == 'newest' ? 'selected' : '' }} value="newest">Sort by latest
                                    </option>
                                    <option {{ $sort_by == 'oldest' ? 'selected' : '' }} value="oldest">Sort by oldest
                                    </option>
                                    <option {{ $sort_by == 'name' ? 'selected' : '' }} value="name">Sort by name
                                    </option>
                                    <option {{ $sort_by == 'price-asc' ? 'selected' : '' }} value="price-asc">Sort by
                                        price: low to high</option>
                                    <option {{ $sort_by == 'price-desc' ? 'selected' : '' }} value="price-desc">Sort
                                        by price: high to low</option>
                                </select>
                            </div>
                        </div>
                        <div class="ps-tabs">
                            <div class="ps-tab active" id="tab-1">
                                <div class="ps-shopping-product">
                                    <div class="row">
                                        @if ($products->count())
                                            @foreach ($products as $product)
                                                <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-6 product-item">
                                                    @include('frontend.inc.product_box', [
                                                        'product' => $product,
                                                    ])
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                {{ $products->appends(request()->input())->links('vendor.pagination.product_listing') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal" id="shop-filter-lastest" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="list-group">
                                <a class="list-group-item list-group-item-action" href="#">Sort by</a>
                                <a class="list-group-item list-group-item-action" href="#">Sort by average
                                    rating</a>
                                <a class="list-group-item list-group-item-action" href="#">Sort by latest</a>
                                <a class="list-group-item list-group-item-action" href="#">Sort by price: low to
                                    high</a>
                                <a class="list-group-item list-group-item-action" href="#">Sort by price: high to
                                    low</a>
                                <a class="list-group-item list-group-item-action text-center" href="#"
                                    data-dismiss="modal">
                                    <strong>Close</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $('#input-search').val('{{ $query }}')
            $('#search-category').val({{ $selected_category }})
        });

        $('.sort_by_select').on('select2:select', function(e) {
            var currentUrl = '{!! request()->fullUrl() !!}';
            var url = new URL(currentUrl);
            url.searchParams.set("sort_by", e.params.data.id);
            var newUrl = url.href;
            window.location = newUrl;
        });

        $('.filterClear').on('click', function() {
            window.location = '{!! request()->url() !!}';
        });

        $(document).ready(function() {
            var currentUrl = '{!! request()->fullUrl() !!}';
            var url = new URL(currentUrl);
            if (url.searchParams.values().next().done) {
                $('.filterClear').hide();
            }
        });
    </script>

    <script>
        var nonLinearSlider = document.getElementById('nonlinear');
        if (typeof nonLinearSlider != 'undefined' && nonLinearSlider != null) {

            let searchParams = new URLSearchParams(window.location.search)
            price_min = 0
            price_max = 10000
            if(searchParams.has('min_price')){
                price_min = searchParams.get('min_price')
            }
            if(searchParams.has('max_price')){
                price_max = searchParams.get('max_price')
            }

            @php
                if( $min_price_slider ==  $max_price_slider){
                    $max_price_slider += 1;
                }
            @endphp

            noUiSlider.create(nonLinearSlider, {
                connect: true,
                behaviour: 'tap',
                start: [price_min, price_max],
                range: {
                    min: {{ $min_price_slider ?? 0 }},
                    max: {{ $max_price_slider ?? 10000 }},
                },
            });
            var nodes = [
                document.querySelector('.ps-slider__min'),
                document.querySelector('.ps-slider__max'),
            ];
            var inputsEls = [
                document.querySelector('#price_min'),
                document.querySelector('#price_max'),
            ];
            nonLinearSlider.noUiSlider.on('update', function(values, handle) {
                nodes[handle].innerHTML = Math.round(values[handle]);
                inputsEls[handle].value = Math.round(values[handle]);
            });
        }
    </script>
@endsection
