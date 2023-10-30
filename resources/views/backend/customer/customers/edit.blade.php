@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Edit Customer</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    <form class="form-horizontal" action="{{ route('customers.update', $customer) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control"
                                    value="{{ $customer->name }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="email" placeholder="Email" class="form-control"
                                    value="{{ $customer->email }}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Phone number</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Phone number" class="form-control"
                                    value="{{ $customer->phone }}" name="phone">
                            </div>
                        </div>

                        <hr>
                        <h6>Reset Password</h6>
                        <span>Fill only if you want to reset password</span>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control password" type="password" name="password"
                                        placeholder="Password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary passwordToggle" type="button">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Confirm Password</label>
                            <div class="col-md-9">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control password" name="password_confirmation"
                                        placeholder="Confirm Password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary passwordToggle" type="button">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>



            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Address</h5>
                </div>
                <div class="card-body">
                    <div class="row gutters-10">
                        @if ($customer->addresses->count() > 0)
                            @foreach ($customer->addresses as $index => $address)
                                <div class="col-lg-6">
                                    <div class="border p-3 pr-5 rounded mb-3 position-relative">
                                        <div>
                                            <span class="w-50 fw-600">Name:</span>
                                            <span class="ml-2">{{ $address->name }}</span>
                                        </div>
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
                                            <div class="position-absolute right-0 bottom-0 pr-2 pb-3">
                                                <span class="badge badge-inline badge-primary">Default</span>
                                            </div>
                                        @endif
                                        <div class="dropdown position-absolute right-0 top-0">
                                            <button class="btn bg-gray px-2" type="button" data-toggle="dropdown">
                                                <i class="la la-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" onclick="edit_address({{ $address->id }})">
                                                    Edit
                                                </a>
                                                @if (!$address->set_default)
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.addresses.set_default', [$customer, $address->id]) }}">Make
                                                        This Default</a>
                                                @endif
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.addresses.destroy', [$customer, $address->id]) }}">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <div class="col-lg-6 mx-auto" onclick="add_new_address()">
                            <div class="border p-3 rounded mb-3 c-pointer text-center bg-light">
                                <i class="la la-plus la-2x"></i>
                                <div class="alpha-7">Add New Address</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('script')
    <div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    <div class="modal-body">
                        <div class="p-3">
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
                                            @foreach ($country as $key => $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
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

                            @if (get_setting('google_map') == 1)
                                <div class="row">
                                    <input id="searchInput" class="controls" type="text"
                                        placeholder="Enter a location">
                                    <div id="map"></div>
                                    <ul id="geoData">
                                        <li style="display: none;">Full Address: <span id="location"></span></li>
                                        <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                                        <li style="display: none;">Country: <span id="country"></span></li>
                                        <li style="display: none;">Latitude: <span id="lat"></span></li>
                                        <li style="display: none;">Longitude: <span id="lon"></span></li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">Longitude</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="longitude" name="longitude"
                                            readonly="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2" id="">
                                        <label for="exampleInputuname">Latitude</label>
                                    </div>
                                    <div class="col-md-10" id="">
                                        <input type="text" class="form-control mb-3" id="latitude" name="latitude"
                                            readonly="">
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-2">
                                    <label>Postal code</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="Your Postal Code"
                                        name="postal_code" value="" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Phone</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="text" class="form-control mb-3" placeholder="+880" name="phone"
                                        value="" required>
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="edit_modal_body">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function add_new_address() {
            $('#new-address-modal').modal('show');
        }

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
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat = -33.8688;
                        var long = 151.2195;

                        if (response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat = response.data.address_data.latitude;
                            long = response.data.address_data.longitude;
                        }

                        initialize(lat, long, 'edit_');
                    @endif
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
                        AIZ.plugins.bootstrapSelect('refresh');
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
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>
@endsection
