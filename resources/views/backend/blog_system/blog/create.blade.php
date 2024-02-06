@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Blog Information')}}</h5>
            </div>
            <div class="card-body">
                <form id="add_form" class="form-horizontal" action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            {{translate('Blog Title')}}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Blog Title')}}" onkeyup="makeSlug(this.value)" id="title" name="title" class="form-control" value="{{ old('title') }}">
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                  
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Slug')}}
                            <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{translate('Slug')}}"  value="{{ old('slug') }}" name="slug" id="slug" class="form-control">
                            @error('slug')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">
                            {{translate('Banner')}} 
                            <small>(415x415)</small>
                        </label>
                        <div class="col-md-9">
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                   
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Description')}}
                        </label>
                        <div class="col-md-9">
                            <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">
                            {{translate('Blog Date')}}
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="blog_date" id="blog_date" class="form-control aiz-date" value="{{ old('blog_date') }}" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <h5 class=" col-lg-3 mb-0 h6">SEO Meta Details</h5>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Meta Title</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title') }}" placeholder="Meta Title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Description</label>
                        <div class="col-lg-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Keywords</label>
                        <div class="col-md-9">
                            {{-- data-max-tags="1" --}}
                            <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                placeholder="Type and hit enter to add a keyword" value="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">OG Title</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="og_title" value="{{ old('og_title') }}" placeholder="OG Title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">OG Description</label>
                        <div class="col-lg-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Twitter Title</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" value="{{ old('twitter_title') }}" name="twitter_title"
                                placeholder="Twitter Title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">Twitter Description</label>
                        <div class="col-lg-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-info">
                            {{translate('Save')}}
                        </button>
                        <a href="{{route('blog.index')}}" class="btn btn-warning">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('admin_assets/assets/css/bootstrap-datepicker3.min.css') }}" />
@endsection

@section('script')
<script src="{{ asset('admin_assets/assets/js/bootstrap-datepicker.js') }}"></script>
<script>
    function makeSlug(val) {
        let str = val;
        let output = str.replace(/\s+/g, '-').toLowerCase();
        $('#slug').val(output);
    }

    $('#blog_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
</script>
@endsection
