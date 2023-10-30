<div class="menu__content">
    <ul class="menu--dropdown">
        @foreach (getMenu(6) as $item)
            @if (count($item['child']))
                <li class="menu-item-has-children has-mega-menu">
                    <a href="{{ $item['link'] }}" title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                    <span class="sub-toggle"></span>
                    @php
                        $menu_class = 'mega-menu-small';
                    @endphp
                    @foreach ($item['child'] as $sec_child)
                        @if ($sec_child['class'] == 'menu-col-1')
                            @php
                                $menu_class = 'mega-menu-large';
                            @endphp
                        @endif
                    @endforeach
                    <div class="mega-menu {{ $menu_class }}">
                        <div class="row">
                            @foreach ($item['child'] as $sec_child)
                                @if ($sec_child['class'] == 'menu-col-1')
                                    <div class="col-md-3">
                                        @foreach ($sec_child['child'] as $third_child)
                                            @if ($third_child['class'] == 'menu-col')
                                                <a href="{{ $third_child['link'] }}" title="{{ $third_child['label'] }}">
                                                    <h4>
                                                        {{ $third_child['label'] }}
                                                        <span class="sub-toggle"></span>
                                                    </h4>
                                                </a>
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
                                            @else
                                                <a href="{{ $item['link'] }}"
                                                    title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <a href="{{ $item['link'] }}"
                                        title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </li>
            @else
                <li>
                    <a href="{{ $item['link'] }}" title="{{ $item['label'] }}">{{ $item['label'] }}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>
