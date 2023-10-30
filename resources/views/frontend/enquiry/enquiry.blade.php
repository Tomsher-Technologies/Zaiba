@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Enquiry List</li>
            </ul>
        </div>
    </div>

    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row justify-content-center">
                    @if ($enquiries && $enquiries->products->count())
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table ps-table--shopping-cart ps-table--responsive">
                                    <thead class="ps-table--shopping-cart-header">
                                        <tr>
                                            <th>Product Details</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enquiries->products as $product)
                                            <tr id="list-item-{{ $product->id }}">
                                                <td data-label="Product">
                                                    <div class="ps-product--cart">
                                                        <div class="ps-product__thumbnail">
                                                            <a href="{{ route('product', $product->slug) }}"
                                                                title="{{ $product->name }}">
                                                                <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                                    alt="{{ $product->name }}"
                                                                    onerror="this.onerror=null;this.src='{{ frontendAsset('img/placeholder.webp') }}';" />
                                                            </a>
                                                        </div>
                                                        <div class="ps-product__content">
                                                            <a href="{{ route('product', $product->slug) }}">
                                                                {{ $product->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td data-label="Actions">
                                                    <a href="#" onclick="removeFromList({{ $product->id }},event)">
                                                        <i class="icon-cross"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button data-bs-toggle="modal" data-bs-target="#new-address-modal" class="ps-btn">Enquire
                                    Now</button>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center">
                                        <img src="{{ frontendAsset('img/cart-empty.svg') }}" alt="" class="w-50">
                                    </div>
                                    <div class="text-center mt-5 pt-1">
                                        <h4 class="mb-3 text-capitalize">Your enquiry list is empty!</h4>
                                        <h5 class="text-muted mb-0">What are you waiting for?</h5>
                                        <div class="mt-4 pt-2 hstack gap-2 justify-content-center">
                                            <a href="{{ route('home') }}" class="btn ps-btn btn-sm">Start Shopping
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Address</h5>
                    <button type="button" class="ps-btn--close  close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" id="enquiry_form">
                    <div class="modal-body">
                        <div class="p-3">

                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="Your Name" name="name"
                                        value="{{ auth()->user() ? auth()->user()->name : '' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Email</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="email" class="form-control mb-3" name="email"
                                        value="{{ auth()->user() ? auth()->user()->email : '' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Phone</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="+971" name="phone"
                                        value="{{ auth()->user() ? auth()->user()->phone : '' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Message</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="Your message" rows="2" name="message" required></textarea>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" id="addressAddFormSubmit" class="ps-btn ps-btn--fullwidth">Enquire
                                    now</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function removeFromList(id, event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: config.routes.enquiry_remove,
                data: {
                    'id': id,
                    '_token': config.csrf
                },
                success: function(data, status, xhr) {
                    // console.log(data);
                    if (xhr.status == 200) {
                        launchToast(data.message);
                        $('.headerEnquiryCount').html(data.count)
                        $('#list-item-' + id).remove()

                        if ($('.ps-table--shopping-cart tbody tr').length <= 0) {
                            $('.table-responsive').html(
                                "<p>You dont have any items to enquire</p>");
                        }
                    } else {
                        launchToast('Something went wrong, please try again', 'error');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    if (xhr.status == 404) {
                        launchToast('Something went wrong, please try again', 'error');
                    }
                },
            });

        }

        $('#enquiry_form').on('submit', function(e) {
            e.preventDefault();
            data = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('enquiry.index') }}",
                data: {
                    'data': data,
                    '_token': config.csrf
                },
                success: function(data, status, xhr) {
                    if (xhr.status == 200) {

                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById(
                            'new-address-modal'));
                        myModal.hide();

                        $('.table-responsive').html(
                            "<p>You dont have any items to enquire</p>");
                    } else {
                        launchToast('Something went wrong, please try again', 'error');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    launchToast('Something went wrong, please try again', 'error');
                },
            });

        });
    </script>
@endsection
