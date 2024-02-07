@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit {{ $page->slug }} Page Information</h1>
            </div>
        </div>
    </div>
    <div class="card">


        <form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH">

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Page Content</h6>
            </div>
            <div class="card-body px-0">
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Contact Form Heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Contact Form Heading') }}" name="title"
                            value="{{ $page->title }}" required>
                            <input type="hidden" name="type" value="{{ $page->type }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="heading1">{{ translate('Detials Heading') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Detials Heading') }}" name="heading1"
                            value="{{ $page->heading1 }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="content">{{ translate('Address') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="content">{{ $page->content }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="sub_heading1">{{ translate('Phone') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Phone') }}" name="sub_heading1"
                            value="{{ $page->sub_heading1 }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="sub_heading2">{{ translate('Email') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" placeholder="{{ translate('Email') }}" name="sub_heading2"
                            value="{{ $page->sub_heading2 }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="heading2">{{ translate('Working Hours') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="heading2">{{ $page->heading2 }}</textarea>
                    </div>
                </div>
                
            </div>

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Seo Fields</h6>
            </div>
            <div class="card-body px-0">

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="meta_title"
                            value="{{ $page->meta_title }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Description') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('Description') }}" name="meta_description">{!! $page->meta_description !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Keywords') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('Keyword, Keyword') }}" name="keywords">{!! $page->keywords !!}</textarea>
                        <small class="text-muted">Separate with coma</small>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('OG Title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('OG Title') }}"
                            name="og_title" value="{{ $page->og_title }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('OG Description') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('OG Description') }}" name="og_description">{!! $page->og_description !!}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Twitter Title') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Twitter Title') }}"
                            name="twitter_title" value="{{ $page->twitter_title }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Twitter Description') }}</label>
                    <div class="col-sm-10">
                        <textarea class="resize-off form-control" placeholder="{{ translate('Twitter Description') }}"
                            name="twitter_description">{!! $page->twitter_description !!}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Meta Image') }}</label>
                    <div class="col-sm-10">
                        <div class="input-group " data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input type="hidden" name="meta_image" class="selected-files"
                                value="{{ $page->meta_image }}">
                        </div>
                        <div class="file-preview">
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-info">Update Page</button>
                    <a href="{{ route('website.pages') }}" class="btn btn-warning">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
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
                zoom: 12,
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
            loadMap({{ $page->heading4 ?? '25.2048' }}, {{ $page->heading5 ?? '55.2708' }})
            // if (navigator.geolocation) {
            //     console.log(navigator.geolocation);
            //     navigator.geolocation.watchPosition(showPosition, showPositionerror);
            // } else {
            //     console.log("asas");
            //     loadMap(25.2048, 55.2708)
            // }
        });
    </script>
@endsection