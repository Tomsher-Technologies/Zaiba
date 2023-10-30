{{-- @php
    $value = null;
    for ($i = 0; $i < $category->level; $i++) {
        $value .= '-';
    }
@endphp
<option value="{{ $category->id }}">
    {{ $value . ' ' . $category->name }}
</option>
@if ($category->child)
    @foreach ($category->child as $childCategory)
        @include('frontend.product.categories.mobile_menu_category', [
            'category' => $childCategory,
        ])
    @endforeach
@endif --}}
<li class="{{ $category->child ? 'menu-item-has-children' : '' }}">
    <a href="{{ route('products.category', $category->slug) }}">{{ $category->name }}</a>
    @if ($category->child && $category->child->count())
        <span class="sub-toggle"></span>
        <ul class="sub-menu">
            @foreach ($category->child as $childCategory)
                @include('frontend.product.categories.mobile_menu_category', [
                    'category' => $childCategory,
                ])
            @endforeach
        </ul>
    @endif
</li>
