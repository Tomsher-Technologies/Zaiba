@extends('frontend.layouts.app')

@section('content')

    @php
        $subtotal = 0;
        $copupn = 0;
        $total = 0;
        $copupn_applied = false;
    @endphp

    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li>Checkout</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                @if ($carts->count())
                    <form class="ps-form--checkout" id="checkoutForm" action="do_action" method="post">
                        <div class="row">
                            <div class="col-xl-7 col-lg-8 col-md-12 col-sm-12  ">
                                <div class="ps-form__billing-info">

                                    {{-- Wizard --}}
                                    <div class="card">
                                        <div class="card-header">
                                            <nav class="nav nav-pills nav-fill">
                                                <a class="nav-link tab-pills" href="#">Shipping Address</a>
                                                <a class="nav-link tab-pills" href="#">Shipping Method</a>
                                                <a class="nav-link tab-pills" href="#">Finish</a>
                                            </nav>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab d-none">

                                                @auth
                                                    <div class="row g-sm-4 g-3">
                                                        @if ($addresses && $addresses->count())
                                                            @foreach ($addresses as $address)
                                                                <div class="col-lg-6">
                                                                    <label
                                                                        class="addressLabel w-100 {{ $address->set_default ? 'checked' : '' }}"
                                                                        for="address-{{ $address->id }}">
                                                                        {{-- {{ $address->set_default ? 'checked' : '' }} --}}
                                                                        <input type="radio" name="address"
                                                                            {{ $address->set_default ? 'checked' : '' }}
                                                                            id="address-{{ $address->id }}"
                                                                            value="{{ $address->id }}"
                                                                            class="addressCheckbox d-none" required>

                                                                        <div
                                                                            class="border p-3 pr-5 rounded mb-3 position-relative">
                                                                            <div>
                                                                                <span class="w-50 fw-600">Name:</span>
                                                                                <span class="ml-2">{{ $address->name }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">Address:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->address }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">Postal
                                                                                    code:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->postal_code }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">City:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->city->name }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">State:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->state->name }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">Country:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->country->name }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="w-50 fw-600">Phone:</span>
                                                                                <span
                                                                                    class="ml-2">{{ $address->phone }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        @endif

                                                        <div class="col-lg-6 mx-auto" id="addAddressContaniner"
                                                            onclick="add_new_address()">
                                                            <div class="border p-3 rounded mb-3 c-poniter text-center bg-light">
                                                                <i class="iconly-Plus icli fs-1"></i>
                                                                <div class="alpha-7 user-select-none">Add New Address</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <form class="form-default" role="form" id="addressAddForm">
                                                        <div class="modal-body">
                                                            <div class="p-3">
                                                                <div class=" row">
                                                                    <label class="col-md-2">Location</label>
                                                                    <div class="col-sm-10">
                                                                        <input type="text" class="form-control"
                                                                            id="us3-address" />
                                                                    </div>
                                                                    <div class="col-sm-12 mt-3">
                                                                        <div id="us3" style="height: 400px;"></div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="latitude" class="form-control"
                                                                    id="us3-lat" />
                                                                <input type="hidden" name="longitude" class="form-control"
                                                                    id="us3-lon" />

                                                                <div class="row mt-3">
                                                                    <div class="col-md-2">
                                                                        <label>Name</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control mb-3"
                                                                            placeholder="Your Name" name="name"
                                                                            value="{{ auth()->user() ? auth()->user()->name : '' }}"
                                                                            required>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>Address</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <textarea class="form-control mb-3" placeholder="Your Address" rows="2" name="address" required></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>Country</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <div class="mb-3">
                                                                            <select class="form-control aiz-selectpicker"
                                                                                data-live-search="true"
                                                                                data-placeholder="Select your country"
                                                                                name="country_id" required>
                                                                                <option value="">Select your country
                                                                                </option>
                                                                                @if ($country)
                                                                                    @foreach ($country as $key => $coun)
                                                                                        <option value="{{ $coun->id }}">
                                                                                            {{ $coun->name }}</option>
                                                                                    @endforeach
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>State</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control mb-3 aiz-selectpicker"
                                                                            data-live-search="true" name="state_id" required>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>City</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <select class="form-control mb-3 aiz-selectpicker"
                                                                            data-live-search="true" name="city_id" required>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>Postal code</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control mb-3"
                                                                            placeholder="Your Postal Code" name="postal_code"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label>Phone</label>
                                                                    </div>
                                                                    <div class="col-md-10">
                                                                        <input type="text" class="form-control mb-3"
                                                                            placeholder="+971" name="phone" value=""
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <button type="submit" id="addressAddFormSubmit"
                                                                        class="ps-btn ps-btn--fullwidth">Save</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endauth

                                                <div class="form-group">
                                                    <div class="ps-checkbox">
                                                        <input class="form-control" type="checkbox"
                                                            id="billing_address_same">
                                                        <label for="billing_address_same">Use a different billing
                                                            address</label>
                                                    </div>
                                                </div>

                                                <div class="ps-form__billing-info d-none" id="billingAddressContainer">
                                                    <h3 class="ps-form__heading">Billing Details</h3>
                                                    <div class="form-group">
                                                        <label>First Name<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            {{-- <input name="billing_name" class="form-control" type="text"> --}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Last Name<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Company Name<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email Address<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="email">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Country<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Phone<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address<sup>*</sup>
                                                        </label>
                                                        <div class="form-group__content">
                                                            <input class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab d-none">
                                                <div id="shipping_charges">

                                                </div>
                                            </div>


                                            <div class="tab d-none">
                                                <p>All Set! Please submit to continue. Thank you</p>
                                            </div>
                                        </div>
                                        <div class="card-footer text-end">
                                            <div class="d-flex justify-content-between">
                                                <button type="button" id="back_button" class="btn btn-link"
                                                    onclick="back()">Back</button>
                                                <button type="button" id="next_button" class="ps-btn"
                                                    onclick="next()">Next</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Wizard --}}


                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-4 col-md-12 col-sm-12  ">
                                <div class="ps-form__total">
                                    <h3 class="ps-form__heading">Order Details</h3>
                                    <div class="content">
                                        <div class="ps-block--checkout-total">
                                            <div class="ps-block__header">
                                                <p>Product</p>
                                                <p>Total</p>
                                            </div>
                                            <div class="ps-block__content">
                                                <table class="table ps-block__products">
                                                    <tbody>
                                                        @foreach ($carts as $cart)
                                                            @php
                                                                $subtotal += $cart->quantity * $cart->price;
                                                                if ($cart->coupon_applied) {
                                                                    $copupn += $cart->discount;
                                                                    $copupn_applied = true;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>
                                                                    <a title="{{ $cart->product->name }}"
                                                                        href="{{ route('product', $cart->product->slug) }}">{{ $cart->product->name }}</a>
                                                                    <p>Quantity:<strong>{{ $cart->quantity }}</strong></p>
                                                                </td>
                                                                <td class="text-end">
                                                                    {{ format_price($cart->quantity * $cart->price) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <h4 class="ps-block__title">Subtotal
                                                    <span>{{ format_price($subtotal) }}</span>
                                                </h4>
                                                @if ($copupn_applied)
                                                    <h4 class="ps-block__title">Coupon discount
                                                        <span>{{ format_price($copupn) }}</span>
                                                    </h4>
                                                @endif
                                                <h3>Total <span>{{ format_price($subtotal - $copupn) }}</span></h3>
                                            </div>
                                        </div>

                                        <button class="ps-btn ps-btn--fullwidth" disabled>
                                            Checkout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <p>
                        You dont have any items in your cart.
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('header')
    <style>
        .accordion-button {
            font-size: inherit;
        }

        .addressLabel.checked .border {
            border-color: #eb6228 !important;
            box-shadow: 0px 0px 5px 0px #eb6228;
        }

        .c-poniter,
        .addressLabel:hover {
            cursor: pointer;
        }

        .modal {
            --bs-modal-width: 630px;
        }

        .pac-container {
            z-index: 99999;
        }
    </style>
@endsection
@section('script')
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
                <form class="form-default" role="form" id="addressAddForm">
                    <div class="modal-body">
                        <div class="p-3">
                            <div class=" row">
                                <label class="col-md-2">Location</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="us3-address" />
                                </div>
                                <div class="col-sm-12 mt-3">
                                    <div id="us3" style="height: 400px;"></div>
                                </div>
                            </div>

                            <input type="hidden" name="latitude" class="form-control" id="us3-lat" />
                            <input type="hidden" name="longitude" class="form-control" id="us3-lon" />

                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="Your Name"
                                        name="name" value="{{ auth()->user() ? auth()->user()->name : '' }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Address</label>
                                </div>
                                <div class="col-md-10">
                                    <textarea class="form-control mb-3" placeholder="Your Address" rows="2" name="address" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Country</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="mb-3">
                                        <select class="form-control aiz-selectpicker" data-live-search="true"
                                            data-placeholder="Select your country" name="country_id" required>
                                            <option value="">Select your country</option>
                                            @if ($country)
                                                @foreach ($country as $key => $coun)
                                                    <option value="{{ $coun->id }}">{{ $coun->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>State</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                        name="state_id" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>City</label>
                                </div>
                                <div class="col-md-10">
                                    <select class="form-control mb-3 aiz-selectpicker" data-live-search="true"
                                        name="city_id" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Postal code</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3 numbers-only"
                                        placeholder="Your Postal Code" name="postal_code" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Phone</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3 numbers-only" placeholder="+971"
                                        name="phone" value="" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" id="addressAddFormSubmit"
                                    class="ps-btn ps-btn--fullwidth">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script>
        $(document).on('change', '.addressCheckbox', function() {
            $('.addressLabel').removeClass('checked');
            $('.addressCheckbox:checked').parent('label').addClass('checked');
            get_shipping_rate()
        }).trigger('change');
        $('#billing_address_same').on('change', function() {
            if ($(this).is(':checked')) {
                $('#billingAddressContainer').removeClass('d-none');
            } else {
                $('#billingAddressContainer').addClass('d-none');
            }
        }).trigger('change');

        // Get shipping rate
        function get_shipping_rate() {
            address_id = $('.addressCheckbox:checked').val();

            // if (address_id) {
            //     console.log(address_id);
            // } else {
            //     console.log('guest');
            // }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('checkout.shipping_methods') }}",
                data: $('#addressAddForm').serialize(),
                type: 'GET',
                success: function(response) {
                    shipping_methods = JSON.stringify(response.shipping_methods)

                    console.log(shipping_methods);
                    // shipping_methods.forEach(element => {
                    //     console.log(element);
                    // });
                }
            });
        }

        get_shipping_rate();


        // add New address
        function add_new_address() {
            $('#new-address-modal').modal('show');
        }


        $('#addressAddForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('addresses.store') }}",
                data: $('#addressAddForm').serialize(),
                type: 'POST',
                success: function(response) {
                    if (response.msg && response.msg == 'success') {
                        $(response.data).insertBefore("#addAddressContaniner");
                        $('#new-address-modal').modal('hide');
                    } else {
                        launchToast('Somting went wrong, please try again', 'error');
                    }
                }
            });
        });


        function edit_address(address) {
            var url = '{{ route('addresses.edit', ':id') }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="state_id"]').html(obj);

                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);

                    }
                }
            });
        }
    </script>

    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&v=weekly"></script>
    <script src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>
    <script>
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            loadMap(lat, lng)
        }

        function showPositionerror() {
            loadMap(25.2048, 55.2708)
        }

        function loadMap(lat, lng) {
            $('#us3').locationpicker({
                location: {
                    latitude: lat,
                    longitude: lng
                },
                radius: 0,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lon'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')
                },
                enableAutocomplete: true,
                onchanged: function(currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });
        }

        $(document).ready(function() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(showPosition, showPositionerror);
            } else {
                loadMap(25.2048, 55.2708)
            }
        });
    </script>

    {{-- Wizard --}}
    <script>
        var current = 0;
        var tabs = $(".tab");
        var tabs_pill = $(".tab-pills");

        loadFormData(current);

        function loadFormData(n) {
            $(tabs_pill[n]).addClass("active");
            $(tabs[n]).removeClass("d-none");
            $("#back_button").attr("disabled", n == 0 ? true : false);
            n == tabs.length - 1 ?
                $("#next_button").text("Submit").removeAttr("onclick") :
                $("#next_button")
                .attr("type", "button")
                .text("Next")
                .attr("onclick", "next()");
        }

        function next() {
            errors = false;

            // console.log("Asd");

            // var validator = $("#checkoutForm").validate({
            //     ignore: ":hidden",
            //     rules: {
            //         address: "required",
            //         billing_name: "required",
            //         // email: {
            //         //     required: true,
            //         // }
            //     }
            // });

            // console.log(validator.valid());;

            // $("#checkoutForm").data('validator').element('#element').valid();
            // var validator = $("#checkoutForm").data('validator');

            if (current == 0) {
                // console.log(current);
                // if (!$('.addressCheckbox').is(':checked')) {
                //     errors = true
                //     alert('Please select an address')
                // }
            }

            // if (!errors) {



            $(tabs[current]).addClass("d-none");
            $(tabs_pill[current]).removeClass("active");
            current++;
            loadFormData(current);
            // }
        }

        function back() {
            $(tabs[current]).addClass("d-none");
            $(tabs_pill[current]).removeClass("active");

            current--;
            loadFormData(current);
        }
    </script>
@endsection
