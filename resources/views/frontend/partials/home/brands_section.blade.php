<div class="ps-product-list brand-section">
    <div class="ps-container">
        <div class="ps-section__header">
            <h3>Popular brands</h3>
        </div>
        <div class="brand-section__content">
            <div class="ps-carousel--nav owl-slider owl-slider2" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000"
                data-owl-gap="10" data-owl-nav="true" data-owl-dots="true" data-owl-item="7" data-owl-item-xs="3"
                data-owl-item-sm="4" data-owl-item-md="4" data-owl-item-lg="7" data-owl-item-xl="7"
                data-owl-duration="1000" data-owl-mousedrag="on">
                @foreach ($brands as $brand)
                    <div class="">
                        <a href="{{ route('products.brand', $brand->slug) }}" title="{{ $brand->name }}">
                            <img src="{{ get_uploads_image($brand->logoImage) }}" alt="{{ $brand->name }}" />
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
