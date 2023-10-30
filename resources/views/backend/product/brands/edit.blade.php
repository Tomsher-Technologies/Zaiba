@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">Brand Information</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">

                <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST"
                    enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">Name <i
                                class="las la-language text-danger" title="Translatable"></i></label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="Name" id="name" name="name"
                                value="{{ $brand->name }}" class="form-control" required>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @livewire('slug-check', ['model' => 'App\\Models\\Brand', 'model_id' => $brand->id, 'template' => 2])

                    <div class="form-group  row">
                        <label class="col-md-3 col-form-label">Is Featured</label>
                        <div class="col-md-9">
                            <select class="select2 form-control" name="top">
                                <option {{ $brand->top == 1 ? 'selected' : '' }} value="1">Yes
                                </option>
                                <option {{ $brand->top == 0 ? 'selected' : '' }} value="0">No
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">Logo
                            <small>(120x80)</small></label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="logo" value="{{ $brand->logo }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            @error('logo')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="Meta Title"
                                value="{{ old('meta_title', $brand->meta_title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', $brand->meta_description) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Meta Keywords</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_keywords"
                                placeholder="Meta Keywords"
                                value="{{ old('meta_keywords', $brand->meta_keywords) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">OG Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="og_title"
                                placeholder="OG Title" value="{{ old('og_title', $brand->og_title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">OG Description</label>
                        <div class="col-md-9">
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description', $brand->og_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="name">Twitter Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="twitter_title"
                                placeholder="Twitter Title"
                                value="{{ old('twitter_title', $brand->twitter_title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Twitter Description</label>
                        <div class="col-md-9">
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description', $brand->twitter_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Footer Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="footer_title"
                                placeholder="Footer Title" value="{{ old('footer_title', $brand->footer_title) }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                            for="name">Footer Description</label>
                        <div class="col-md-9">
                            <textarea name="footer_description" rows="5" class="form-control aiz-text-editor">{{ old('footer_description', $brand->footer_content) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @livewireScripts
    @livewireStyles
@endsection
