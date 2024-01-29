@php
    $value = null;
    for ($i = 0; $i < $category->level; $i++) {
        $value .= '-';
    }
@endphp
<option {{ isset($old_data) ? ( $old_data == $category->id ? 'selected' : '' ) : '' }} value="{{ $category->id }}">{{ $value . ' ' . $category->name }}</option>
@if ($category->child)
    @foreach ($category->child as $childCategory)
        @include('backend.product.categories.menu_child_category', [
            'category' => $childCategory,
        ])
    @endforeach
@endif