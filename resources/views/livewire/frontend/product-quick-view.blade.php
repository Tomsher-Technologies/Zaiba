<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <span class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i class="icon-cross2"></i></span>
        @if ($showLoading == false && $product)
            <article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
                <div class="ps-product__header">
                    <div class="ps-product__thumbnail" data-vertical="false">
                        <div class="ps-product__images" data-arrow="true">
                            <div class="item">
                                <img src="img/products/shop/01.jpg" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="ps-product__info">
                        <h1>
                            {{ $product->name }}
                        </h1>
                        <div class="ps-product__meta">

                            @if ($product->brand)
                                <p>Brand:
                                    <a href="{{ route('products.brand', $product->brand->slug) }}"
                                        title="{{ $product->brand->name }}"> {{ $product->brand->name }}
                                    </a>
                                </p>
                            @endif

                            {{ renderStarRating($product->rating) }}

                        </div>
                        <h4 class="ps-product__price">AED36.78 – AED56.99</h4>
                        <div class="ps-product__desc">
                            <ul class="ps-list--dot">
                                <li> Designed for use in severe industrial, environments</li>
                                <li> High vibration stability</li>
                                <li> MBC 5100 with all major ship approvals</li>
                                <li> High repeatability</li>
                                <li> Optimal compact design for machine building purposes</li>
                            </ul>
                        </div>
                        <div class="ps-product__shopping">
                            <a class="ps-btn" href="#">Add to cart</a>
                            <a class="ps-btn ps-btn--orange" href="#">Buy Now</a>
                            <div class="ps-product__actions">
                                <a href="#"><i class="icon-heart"></i></a>
                                <a href="#"><i class="icon-chart-bars"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @else
            <img src="{{ frontendAsset('img/Loading_icon.gif') }}" alt="Loading">
        @endif
    </div>
</div>
