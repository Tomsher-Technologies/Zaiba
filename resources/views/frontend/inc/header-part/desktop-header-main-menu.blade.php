<ul class="menu">

    @php
        $header_menu = getMenu(1);
    @endphp

    @foreach ($header_menu as $menu)
        @if ($menu['child'])
            <li class="menu-item-has-children has-mega-menu">
                <a title="{{ $menu['label'] }}">{{ $menu['label'] }}</a>
                <span class="sub-toggle"></span>
                <div class="mega-menu">
                    <div class="mega-menu__columnone">
                        <h4>Categories<span class="sub-toggle"></span></h4>
                        <ul class="mega-menu__list">

                            @foreach ($menu['child'] as $child)
                                <li class="">
                                    <a href="{{ $child['link'] }}" title="{{ $child['label'] }}">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>

                    @if ($menu['img_1'] !== null)
                        <div class="mega-menu__columntwo">
                            <a href="{{ $menu['img_1_link'] }}">
                                <img class="w-100" src="{{ $menu['img_1_src'] }}" alt="">
                            </a>
                        </div>
                    @endif
                    @if ($menu['brands'] !== null)
                        <div class="mega-menu__columnthree">
                            <div class="menu-shop-brands">
                                <div class="row">
                                    @foreach ($menu['brands'] as $item)
                                        <div class="brand col-lg-4">
                                            <a href="{{ route('products.brand', $item->slug) }}">
                                                <img class="w-100" src="{{ get_uploads_image($item->logoImage) }}"
                                                    alt="{{ $item->name }}">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($menu['img_2'] || $menu['img_3'])
                        <div class="mega-menu__columnfour">
                            <div class="row">
                                @if ($menu['img_2'] !== null)
                                    <div class="col-md-12 pb-5">
                                        <a href="{{ $menu['img_2_link'] }}">
                                            <img class="w-100" src="{{ $menu['img_2_src'] }}"
                                                alt="{{ $menu['label'] }}" />
                                        </a>
                                    </div>
                                @endif
                                @if ($menu['img_3'] !== null)
                                    <div class="col-md-12">
                                        <a href="{{ $menu['img_3_link'] }}">
                                            <img class="w-100" src="{{ $menu['img_3_src'] }}"
                                                alt="{{ $menu['label'] }}" />
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </li>
        @else
            <li>
                <a href="{{ $menu['link'] }}" title="{{ $menu['label'] }}">{{ $menu['label'] }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
