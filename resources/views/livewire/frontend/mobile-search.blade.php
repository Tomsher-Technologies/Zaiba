<div>
    @php
        $has_results = false;
    @endphp
    <div class="ps-panel__header">
        <form wire:submit.prevent="search()" class="ps-form--search-mobile" id="mobile-search">
            <div class="form-group--nest">
                <input class="form-control" wire:model.debounce.500ms="query" name="query" type="text"
                    placeholder="Search something...">
                <button type="button"><i class="icon-magnifier"></i></button>
            </div>
        </form>
    </div>
    <div class="navigation__content" id="mobile-search-result">
        <div class="search__suggestions suggestions suggestions--location--header">

            <div class="p-5" wire:loading>
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="m-0 fs-14 text-muted fw-semibold"> Loading </h4>
                    </div>
                </div>
            </div>

            @if ($this->products && $this->products->count())
                @php
                    $has_results = true;
                @endphp
                <div wire:loading.hide>
                <p class="py-3 px-4 m-0 fs-14 text-muted fw-semibold">
                    In products
                </p>
                <ul class="suggestions__list">
                    @foreach ($this->products as $product)
                        <li class="suggestions__item ng-star-inserted">
                            <div class="suggestions__item-image product-image">
                                <div class="product-image__body">
                                    <img alt="{{ $product->name }}" class="product-image__img"
                                        src="{{ $product->thumbnail_img ? uploaded_asset($product->thumbnail_img) : frontendAsset('img/placeholder.webp') }}">
                                </div>
                            </div>
                            <div class="suggestions__item-info">
                                <a class="suggestions__item-name" href="{{ route('product', $product->slug) }}"
                                    title="{{ $product->name }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                            <div class="suggestions__item-price"> {{ home_discounted_base_price($product) }}
                                @if (home_base_price($product) != home_discounted_base_price($product))
                                    <br> <del>{{ home_base_price($product) }}</del>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
                </div>
            @endif

            @if ($this->categories && $this->categories->count())
                @php
                    $has_results = true;
                @endphp
                <div wire:loading.hide>
                    <p class="py-3 px-4 m-0 fs-14 text-muted fw-semibold">
                        In categories
                    </p>
                    <ul class="suggestions__list">
                        @foreach ($this->categories as $category)
                            <li class="suggestions__item ng-star-inserted">
                                <div class="suggestions__item-image product-image">
                                    <div class="product-image__body">
                                        <img alt="{{ $category->name }}" class="product-image__img"
                                            src="{{ frontendAsset('img/placeholder.webp') }}">
                                    </div>
                                </div>
                                <div class="suggestions__item-info">
                                    <a class="suggestions__item-name"
                                        href="{{ route('products.category', $category->slug) }}"
                                        title="{{ $category->name }}">
                                        {{ $category->name }}
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!$first_load && !$has_results)
                <div class="p-5" wire:loading.hide>
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="m-0 fs-14 text-muted fw-semibold"> Not items found </h4>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- <div wire:loading>
            loading
        </div>
        <ul>
            
        </ul> --}}
    </div>
</div>
