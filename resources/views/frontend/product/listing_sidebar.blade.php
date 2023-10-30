<div class="ps-layout__left sidebar">
    <aside class="widget widget_shop">
        <form action="" id="sidebarForm" method="get">
            @isset($sort_by)
                <input type="hidden" name="sort_by" value="{{ $sort_by }}">
            @endisset
            <figure>
                <h4 class="widget-title">Search</h4>
                <div class="ps-form--widget-search ">
                    <label for="sidebar-search" class="d-none">Search</label>
                    <input id="sidebar-search" name="keyword" class="form-control" type="text"
                        value="{{ $query }}" placeholder="">
                    <button aria-label="Search"><i class="icon-magnifier"></i></button>
                </div>
            </figure>
            <figure class="ps-custom-scrollbar" data-height="250">
                <h4 class="widget-title">Categories</h4>
                <ul class="ps-list--categories">
                    @foreach ($category as $cat)
                        @include('frontend.product.categories.child_category', [
                            'category' => $cat,
                            'selected_id' => $side_categories,
                        ])
                    @endforeach
                </ul>
            </figure>
            <figure class="ps-custom-scrollbar" data-height="250">
                <h4 class="widget-title">BY BRANDS</h4>
                @foreach ($brands as $key => $brand)
                    <div class="ps-checkbox">
                        <input value="{{ $brand->id }}" {{ in_array($brand->id, $side_brands) ? 'checked' : '' }}
                            {{ $brand_id == $brand->id ? 'checked' : '' }} class="form-control brands-select"
                            type="checkbox" id="brand-{{ $key }}" />
                        <label for="brand-{{ $key }}">
                            {{ $brand->name }}
                        </label>
                    </div>
                @endforeach
            </figure>
            <figure>
                <h4 class="widget-title">By Price</h4>
                <div id="nonlinear"></div>

                <input type="hidden" name="min_price" id="price_min">
                <input type="hidden" name="max_price" id="price_max">

                <p class="ps-slider__meta">Price:<span class="ps-slider__value">{{ Session::get('currency_code') }}<span
                            class="ps-slider__min"></span></span>-<span
                        class="ps-slider__value">{{ Session::get('currency_code') }}<span
                            class="ps-slider__max"></span></span></p>
            </figure>
            <figure>
                <h4 class="widget-title">By Rating</h4>
                <div class="ps-checkbox">
                    <input class="form-control rating-select" type="checkbox" id="review-1" value="5">
                    <label for="review-1">
                        <span>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                        </span>
                    </label>
                </div>
                <div class="ps-checkbox">
                    <input class="form-control rating-select" type="checkbox" id="review-2" value="4">
                    <label for="review-2">
                        <span>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </label>
                </div>
                <div class="ps-checkbox">
                    <input class="form-control rating-select" type="checkbox" id="review-3" value=""3>
                    <label for="review-3">
                        <span>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </label>
                </div>
                <div class="ps-checkbox">
                    <input class="form-control rating-select" type="checkbox" id="review-4" value="2">
                    <label for="review-4">
                        <span>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </label>
                </div>
                <div class="ps-checkbox">
                    <input class="form-control rating-select" type="checkbox" id="review-5" value="1">
                    <label for="review-5">
                        <span>
                            <i class="fa fa-star rate"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </span>
                    </label>
                </div>
            </figure>

            <input type="hidden" id="categories" name="categories">
            <input type="hidden" id="brands" name="brands">
            <input type="hidden" id="rating" name="rating">

            <figure>
                <button type="submit" class="ps-btn">Filter</button>
                <button type="button" class="ps-btn filterClear">Clear</button>
            </figure>
        </form>
    </aside>
</div>
<script>
    // var arrayClean = function(thisArray, thisName) {
    //     "use strict";
    //     $.each(thisArray, function(index, item) {
    //         if (item.name == thisName) {
    //             delete thisArray[index];
    //         }
    //     });
    // }



    $('#sidebarForm').on('submit', function(e) {
        e.preventDefault();

        categories = [];
        $('.category-select:checkbox:checked').each(function() {
            categories.push($(this).val())
        })
        $('#categories').val(categories);

        brands = [];
        $('.brands-select:checkbox:checked').each(function() {
            brands.push($(this).val())
        })
        $('#brands').val(brands);

        rating = [];
        $('.rating-select:checkbox:checked').each(function() {
            rating.push($(this).val())
        })
        $('#rating').val(rating);

        this.submit();
    });
</script>
