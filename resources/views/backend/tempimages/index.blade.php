@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 mx-auto">

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Upload Temp Images</h5>
                </div>

                <form class="form-horizontal" method="POST" action="{{ route('temp_image') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Images') }}</label>
                            <div class="col-sm-9">
                                <input type="file" name="files[]" multiple placeholder="{{ translate('Images') }}"
                                    id="name[]" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Download File</h5>
                </div>
                <div class="card-body">
                    <div class="alert"
                        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        Download excel file with uploaded image SKU and URLs
                    </div>
                    <br>
                    <div class="">
                        <a href="{{ route('temp_image.all') }}" download=""><button
                                class="btn btn-info">Download</button></a>
                    </div>

                    <br>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Delete Temporary Images</h5>
                </div>
                <div class="card-body">
                    <div class="alert"
                        style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                        Delete all temporary images to save storage space.
                    </div>
                    <br>
                    <div class="">
                        <form action="{{ route('temp_image.delete') }}" method="post">
                            @csrf
                            <button class="btn btn-danger">Delete</button>
                        </form>
                    </div>

                    <br>
                </div>
            </div>


        </div>
    </div>
@endsection
