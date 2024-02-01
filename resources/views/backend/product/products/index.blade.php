@extends('backend.layouts.app')

@section('content')
<style>
    .bread .breadcrumb {
        all: unset;
    }

    .bread .breadcrumb li {
        display: inline-block;
    }

    .bread nav {
        display: inline-block;
        max-width: 250px;
    }

    .bread .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
    }

    .breadcrumb-item+.breadcrumb-item {
        padding-left: 0;
    }

    .bread a {
        pointer-events: none;
        cursor: sw-resize;
    }
</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="h3">All products</h1>
            </div>
            @if ($type != 'Seller')
                <div class="col text-right">
                    <a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
                        <span>Add New Product</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">All Product</h5>
                </div>

               
                <div class="col-md-2 ml-auto bootstrap-select">
                    
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" data-live-search="true"
                            name="category" id="" data-selected={{ $category }}>
                        <option value="0">All</option>
                        @foreach (getAllCategories()->where('parent_id', 0) as $item)
                            <option value="{{ $item->id }}" @if( $category == $item->id)  {{ 'selected' }} @endif )>{{ $item->name }}</option>
                            @if ($item->child)
                                @foreach ($item->child as $cat)
                                    @include('backend.product.categories.menu_child_category', [
                                        'category' => $cat,
                                        'old_data' => $category,
                                    ])
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 ml-auto bootstrap-select">
                    <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-md-0" name="type" id="type"
                        onchange="sort_products()">
                        <option value="">Sort By</option>
                        <option value="rating,desc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                            Rating (High > Low)</option>
                        <option value="rating,asc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                            Rating (Low > High)</option>
                        <option
                            value="num_of_sale,desc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>
                            Num of Sale (High > Low)</option>
                        <option
                            value="num_of_sale,asc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>
                            Num of Sale (Low > High)</option>
                        <option
                            value="unit_price,desc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>
                            Base Price (High > Low)</option>
                        <option
                            value="unit_price,asc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'asc') selected @endif @endisset>
                            Base Price (Low > High)</option>
                        <option
                            value="status,1"@isset($col_name, $query) @if ($col_name == 'status' && $query == '1') selected @endif @endisset>
                            Published</option>
                        <option
                            value="status,0"@isset($col_name, $query) @if ($col_name == 'status' && $query == '0') selected @endif @endisset>
                            Unpublished</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type & Enter">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info " type="submit">Filter</button>
                    <a href="{{ route('products.all') }}" class="btn btn-warning">Reset</a>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            {{-- <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th> --}}
                            <th>#</th>
                            <th>{{ translate('Name') }}</th>
                            <th >Category</th>
                            <th >{{ translate('Info') }}</th>
                            <th class="text-center">{{ translate('Total Stock') }}</th>
                            {{-- <th data-breakpoints="lg">{{translate('Todays Deal')}}</th> --}}
                            <th class="text-center">{{ translate('Published') }}</th>
                            {{-- <th data-breakpoints="lg">{{translate('Featured')}}</th> --}}
                            <th class="text-center">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                {{-- <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]"
                                                value="{{ $product->id }}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td> --}}
                                <td>
                                    <div class="row gutters-5 w-200px w-md-250px mw-100">

                                        @if ($product->thumbnail_img)
                                            <div class="col-auto">
                                                <img src="{{ get_product_image($product->thumbnail_img, '300') }}"
                                                    alt="Image" class="size-50px img-fit">
                                            </div>
                                        @endif


                                        <div class="col">
                                            <span class="text-muted text-truncate-2">{{ $product->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="bread">
                                    {{ Breadcrumbs::render('product_admin', $product) }}
                                </td>
                                <td>
                                    <strong>{{ translate('Num of Sale') }}:</strong> {{ $product->num_of_sale }}
                                    {{ translate('times') }} <br>
                                    {{-- <strong>{{ translate('Base Price') }}:</strong> --}}
                                    {{-- {{ single_price($product->unit_price) }} </br> --}}
                                    <strong>{{ translate('Rating') }}:</strong> {{ $product->rating }} <br>
                                    <strong>{{ translate('SKU') }}:</strong> {{ $product->sku }} <br>
                                </td>
                                <td class="text-center">
                                    @php
                                        $qty = 0;
                                        if ($product->product_type == 1) {
                                            foreach ($product->stocks as $key => $stock) {
                                                $qty += $stock->qty;
                                                echo $stock->sku . ' - <b>' . $stock->qty . '</b><br>';
                                            }
                                        } else {
                                            //$qty = $product->current_stock;
                                            $qty = optional($product->stocks->first())->qty;
                                            echo $qty;
                                        }
                                    @endphp
                                    @if ($qty <= $product->low_stock_quantity)
                                        <span class="badge badge-inline badge-danger">Low</span>
                                    @endif
                                </td>
                                {{-- <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_todays_deal(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->todays_deal == 1) {
                                    echo 'checked';
                                } ?> >
                                <span class="slider round"></span>
                            </label>
                        </td> --}}
                                <td class="text-center">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input onchange="update_published(this)" value="{{ $product->id }}"
                                            type="checkbox" <?php if ($product->published == 1) {
                                                echo 'checked';
                                            } ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                {{-- <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if ($product->featured == 1) {
                                    echo 'checked';
                                } ?> >
                                <span class="slider round"></span>
                            </label>
                        </td> --}}
                                <td class="text-center">
                                    <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                        href="{{ route('product', $product->slug) }}" target="_blank" title="View">
                                        <i class="las la-eye"></i>
                                    </a>
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        href="{{ route('products.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                        title="Edit">
                                        <i class="las la-edit"></i>
                                    </a>
                                    {{-- <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )}}" title="Duplicate">
                                <i class="las la-copy"></i>
                            </a> --}}
                                    {{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                        data-href="{{ route('products.destroy', $product->id) }}" title="Delete">
                                        <i class="las la-trash"></i>
                                    </a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        $(document).ready(function() {
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Todays Deal updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Published products updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function update_approved(el) {
            if (el.checked) {
                var approved = 1;
            } else {
                var approved = 0;
            }
            $.post('{{ route('products.approved') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                approved: approved
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Product approval update successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function update_featured(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Featured products updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-product-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
