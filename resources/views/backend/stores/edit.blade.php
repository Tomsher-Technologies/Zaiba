@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Edit Store Details') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Store Information') }}</h5>
            </div>
            <div class="card-body">
                <form id="formMap" action="{{ route('admin.stores.update', $stores[0]->id) }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ translate('Name') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                class="form-control"  value="{{ old('name',$stores[0]->name) }}">
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Address') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="address" id="address" cols="30" rows="3"
                                placeholder="{{ translate('Address') }}" >{{ old('address',$stores[0]->address) }}</textarea>
                            @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Phone') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Phone') }}" id="phone" name="phone"  value="{{ old('phone',$stores[0]->phone) }}"
                                class="form-control" >
                            @error('phone')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Email') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Email') }}" id="email"  value="{{ old('email',$stores[0]->email) }}"
                                name="email" class="form-control" >
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="working_hours">{{ translate('Working Hours') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Working Hours') }}" id="working_hours" name="working_hours" class="form-control"  value="{{ old('working_hours',$stores[0]->working_hours) }}" autocomplete="off">
                            @error('working_hours')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{ translate('Active Status') }}</label>
                        <div class="col-md-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1" @if ($stores[0]->status == 1) checked @endif>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="email">{{ translate('Location') }}<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            {{-- <textarea class="form-control" name="address" id="us7-address" cols="30" rows="3"
                                placeholder="{{ translate('Address') }}" ></textarea> --}}
                            <input type="text" class="form-control" id="us3-address" />

                            {{-- <input type="text" id="us7-address" name="email" class="form-control"> --}}
                        </div>
                        <div class="col-sm-12 mt-3">
                            <div id="us3" style="height: 400px;"></div>
                        </div>
                    </div>

                    <input type="hidden" name="lat" class="form-control" id="us3-lat" value="{{ $stores[0]->latitude}}" />
                    <input type="hidden" name="long" class="form-control" id="us3-lon" value="{{ $stores[0]->longitude}}" />
                   
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-warning">{{ translate('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&v=weekly"></script>
    <script src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>
    <script>
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            loadMap(lat, lng)
        }

        function showPositionerror() {
            // loadMap(25.2048, 55.2708)
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
            loadMap({{ $stores[0]->latitude}}, {{ $stores[0]->longitude}})
        });
    </script>
@endsection
