@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">All Categories</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('categories.create') }}" class="btn btn-info">
                    <span>Add new category</span>
                </a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-block d-md-flex">
            <h5 class="mb-0 h6 mr-4">Categories</h5>
            <form class="" id="sort_categories" action="" method="GET" style="width: 100%">

                <div class="row gutters-5">
                    <div class="col-md-4">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true"
                            name="catgeory" id="" data-selected={{ $catgeory }}>
                            <option value="0">All</option>
                            @foreach (getAllCategories()->where('parent_id', 0) as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @if ($item->child)
                                    @foreach ($item->child as $cat)
                                        @include('backend.product.categories.menu_child_category', [
                                            'category' => $cat,
                                            'selected_id' => 0,
                                        ])
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type name & Enter">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-info" type="submit">Filter</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-warning">Reset</a>
                    </div>
                </div>

            </form>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th >#</th>
                        <th>Name</th>
                        <th >Parent Category</th>
                        {{-- <th >Link</th> --}}
                        <th class="text-center">Slug</th>
                        {{-- <th data-breakpoints="lg">Level</th> --}}
                        <th >Banner</th>
                        <th >Icon</th>
                        <th class="text-center">Status</th>
                        <th width="10%" class="text-right">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => $category)
                        <tr>
                            <td>{{ $key + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @php
                                    $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                                @endphp
                                @if ($parent != null)
                                    {{ $parent->name }}
                                @else
                                    —
                                @endif
                            </td>
                           
                            <td class="text-center">{{ $category->slug }}</td>
                            {{-- <td>{{ $category->level }}</td> --}}
                            <td>
                                @if ($category->banner != null)
                                    <img src="{{ uploaded_asset($category->banner) }}" alt="Banner" class="h-50px">
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($category->icon != null)
                                    <span class="avatar avatar-square avatar-xs">
                                        <img src="{{ uploaded_asset($category->icon) }}" alt="icon">
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" onchange="update_status(this)" value="{{ $category->id }}"
                                        <?php if ($category->is_active == 1) {
                                            echo 'checked';
                                        } ?>>
                                    <span></span>
                                </label>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                    href="{{ route('categories.edit', ['id' => $category->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                    title="Edit">
                                    <i class="las la-edit"></i>
                                </a>
                                {{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('categories.destroy', $category->id) }}" title="Delete">
                                    <i class="las la-trash"></i>
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $categories->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection


@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script>
        function copy(that) {
            var inp = document.createElement('input');
            document.body.appendChild(inp)
            inp.value = that.textContent
            inp.select();
            document.execCommand('copy', false);
            inp.remove();
        }
    </script>
    <script type="text/javascript">
        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Featured categories updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
        function update_status(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('categories.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Category status updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
