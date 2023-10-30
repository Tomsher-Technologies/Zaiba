@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">My Account</a></li>
                <li>Addresses</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row">
                    @include('frontend.partials.dashboard.sidebar')
                    <div class="col-xxl-8 col-lg-8">
                        <div class="dashboard-right-sidebar">
                            <div class="tab-content">
                                <div class="total-box">
                                    <div class="row g-sm-4 g-3">
                                        @if ($addresses)
                                            @foreach ($addresses as $address)
                                                <div class="col-lg-6">
                                                    <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                                        <div>
                                                            <span class="w-50 fw-600">Address:</span>
                                                            <span class="ml-2">{{ $address->address }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="w-50 fw-600">Postal code:</span>
                                                            <span class="ml-2">{{ $address->postal_code }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="w-50 fw-600">City:</span>
                                                            <span class="ml-2">{{ $address->city->name }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="w-50 fw-600">State:</span>
                                                            <span class="ml-2">{{ $address->state->name }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="w-50 fw-600">Country:</span>
                                                            <span class="ml-2">{{ $address->country->name }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="w-50 fw-600">Phone:</span>
                                                            <span class="ml-2">{{ $address->phone }}</span>
                                                        </div>
                                                        @if ($address->set_default)
                                                            <div class="position-absolute end-0 bottom-0 pe-2 pb-3">
                                                                <span class="badge badge-soft-success">Default</span>
                                                            </div>
                                                        @endif
                                                        <div class="dropdown position-absolute end-0 top-0 pe-2 pb-3">

                                                            <button class="btn bg-gray px-2" style="font-size: 22px"
                                                                type="button" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="iconly-Arrow-Down-Circle icli"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-right"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item"
                                                                    onclick="edit_address({{ $address->id }})">
                                                                    Edit
                                                                </a>
                                                                @if (!$address->set_default)
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        onclick="makeDefault('{{ $address->id }}')">Make
                                                                        This Default</a>
                                                                @endif
                                                                <a class="dropdown-item"
                                                                    href="{{ route('addresses.destroy', $address->id) }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function makeDefault(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('addresses.set_default') }}",
                data: {
                    'id': id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data, status, xhr) {
                    if (xhr.status == 200) {
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 404) {
                        location.reload();
                    }
                    if (xhr.status == 401) {
                        launchToast("Please try again", 'error');
                    }
                },
            });

        }

        $('.wishlistRemove').on('click', function() {
            loop_id = $(this).data('loop-id');
            list_id = $(this).data('list-id');
            $(this).attr('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('wishlists.remove') }}",
                data: {
                    'id': list_id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(data) {
                    var rdata = JSON.parse(data);
                    if (rdata.status == 200) {
                        $('[data-loop-container="' + loop_id + '"]').remove();
                        if ($('.ps-table--shopping-cart tbody tr').length <= 0) {
                            $('.table-responsive').html(
                                "<p>You dont have any items in your wishlist</p>");
                        }
                        $('.headerWishlistCount').html(rdata.count)
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 404) {
                        location.reload();
                    }
                },
            });
        });
    </script>
@endsection
