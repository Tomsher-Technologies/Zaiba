<div class="form-group row">
    <label class="col-md-3 col-form-label">Link</label>
    <div class="col-md-9">
        <select class="form-control aiz-selectpicker" name="link_ref_id" id="link_ref_id" data-live-search="true" required>
            @foreach ($categories as $category)
                <option {{ $old_data == $category->id ? 'selected' : '' }} value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
                @foreach ($category->childrenCategories as $childCategory)
                    @include('categories.child_category', [
                        'child_category' => $childCategory,
                        'old_data' => $old_data,
                    ])
                @endforeach
            @endforeach
        </select>
    </div>
</div>
<script>
    $('#link_ref_id').selectpicker({
        size: 5,
        virtualScroll: false
    });
</script>
