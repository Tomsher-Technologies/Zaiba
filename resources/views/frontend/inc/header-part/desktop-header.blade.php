    <!-- header start -->
    <header class="header header--1 header--standards" data-sticky="true">
        @include('frontend.inc.header-part.header-top')
        <nav class="navigation navbar">
            <div class="ps-container">
                <div class="navigation__left">
                    <div class="menu--product-categories">
                        <div class="menu__toggle"><i class="fa fa-bars"></i><span> Shop by Categories</span></div>
                        <div class="menu__content">
                            <ul class="menu--dropdown">
                                @foreach (getMenu(6) as $item)
                                    @if (count($item['child']))
                                        <li class="menu-item-has-children has-mega-menu">
                                            <a href="{{ $item['link'] }}"
                                                title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                                            <span class="sub-toggle"></span>
                                            @php
                                                $menu_class = 'mega-menu-small';
                                                $sub_menu_class = 'col-md-6';
                                                $img_menu_class = 'col-md-6';
                                                $has_menu = false;
                                            @endphp
                                            @foreach ($item['child'] as $sec_child)
                                                @if ($sec_child['class'] == 'menu-col-1')
                                                    @php
                                                        $has_menu = true;
                                                        $menu_class = 'mega-menu-large';
                                                    @endphp
                                                @elseif($item['img_1'])
                                                    @php
                                                        $menu_class = 'mega-menu-medium';
                                                    @endphp
                                                @endif

                                                @if ($item['img_1'])
                                                    @php
                                                        $sub_menu_class = 'col-md-4';
                                                    @endphp
                                                @endif
                                            @endforeach

                                            <div class="mega-menu {{ $menu_class }}">
                                                <div class="row">

                                                    @if ($has_menu)
                                                        @foreach ($item['child'] as $sec_child)
                                                            @if ($sec_child['class'] == 'menu-col-1')
                                                                @php
                                                                    $img_menu_class = 'col-md-4';
                                                                @endphp
                                                                <div class="{{ $sub_menu_class }}">
                                                                    <ul class="mega-menu__list">
                                                                        @foreach ($sec_child['child'] as $third_child)
                                                                            <li>
                                                                                @if ($third_child['class'] == 'menu-col')
                                                                                    <a href="{{ $third_child['link'] }}"
                                                                                        title="{{ $third_child['label'] }}">
                                                                                        <h4>
                                                                                            {{ $third_child['label'] }}
                                                                                            <span
                                                                                                class="sub-toggle"></span>
                                                                                        </h4>
                                                                                    </a>
                                                                                    @if($third_child['child'])
                                                                                    <ul class="mega-menu__list">
                                                                                        @foreach ($third_child['child'] as $forth_child)
                                                                                            <li>
                                                                                                <a href="{{ $forth_child['link'] }}"
                                                                                                    title="{{ $forth_child['label'] }}">
                                                                                                    {{ $forth_child['label'] }}
                                                                                                </a>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                    @endif
                                                                                    
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <div class="{{ $item['img_1'] ? 'col-md-6' : 'col-md-12' }}">
                                                            <ul class="mega-menu__list">
                                                                @foreach ($item['child'] as $sec_child)
                                                                    <li>
                                                                        <a class="pb-10"
                                                                            href="{{ $sec_child['link'] }}"
                                                                            title="{{ $sec_child['label'] }}">
                                                                            <h4>{{ $sec_child['label'] }}</h4>
                                                                        </a>
                                                                        @if ($sec_child['child'])
                                                                            <ul class="">
                                                                                @foreach ($sec_child['child'] as $third_child)
                                                                                    <li>
                                                                                        <a href="{{ $third_child['link'] }}"
                                                                                            title="{{ $third_child['label'] }}">
                                                                                            {{ $third_child['label'] }}
                                                                                        </a>
                                                                                    </li>
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif


                                                    @if ($item['img_1'])
                                                        <div class="{{ $img_menu_class }}">
                                                            <a href="{{ $item['img_1_link'] ?? '#' }}">
                                                                <img class="w-100" src="{{ $item['img_1_src'] }}"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ $item['link'] }}"
                                                title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navigation__right">
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
                                            <!--<h4>Categories<span class="sub-toggle"></span></h4>-->
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
                                                    <img class="w-100" src="{{ $menu['img_1_src'] }}"
                                                        alt="">
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
                                                                    <img class="w-100"
                                                                        src="{{ get_uploads_image($item->logoImage) }}"
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


                    @if (get_setting('show_currency_switcher') == 'on')
                        @php
                            $currentCurrency = getCurrentCurrency();
                            session(['currency_code' => $currentCurrency->code]);
                        @endphp
                        <ul class="navigation__extra" id="currency-change">
                            <li>
                                <div class="ps-dropdown">
                                    <a href="#">{{ $currentCurrency->name }}{{ $currentCurrency->symbol }}</a>
                                    <ul class="ps-dropdown-menu">
                                        @foreach (getCurrency() as $key => $currency)
                                            <li>
                                                <a class=" @if ($currentCurrency->code == $currency->code) active @endif"
                                                    href="javascript:void(0)"
                                                    data-currency="{{ $currency->code }}">{{ $currency->name }}
                                                    ({{ $currency->symbol }})
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>
    </header>
    <style>
        .mega-menu.mega-menu-small .pb-10:last-child {
            padding-bottom: 0;
        }
    </style>
