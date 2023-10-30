<li class="{{ $category->child ? 'menu-item-has-children' : '' }}">
    <div class="ps-checkbox">
        <input {{ $selected_id ? ( in_array($category->id, $selected_id) ? 'checked' : '') : '' }} value="{{ $category->id }}"
            class="form-control category-select" type="checkbox" id="category-{{ $category->slug }}" />
        <label for="category-{{ $category->slug }}">{{ $category->name }}</label>
    </div>
    @if ($category->child && $category->child->count())
        <span class="sub-toggle">
            <i class="fa fa-angle-down"></i>
        </span>
        <ul class="sub-menu">
            @foreach ($category->child as $cat)
                @include('frontend.product.categories.child_category', [
                    'category' => $cat,
                ])
            @endforeach
        </ul>
    @endif
</li>
