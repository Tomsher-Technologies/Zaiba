@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">Category Information</h5>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body p-0">
                    <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST"
                        enctype="multipart/form-data">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name <i
                                    class="las la-language text-danger" title="Translatable"></i></label>
                            <div class="col-md-9">
                                <input type="text" name="name" value="{{ $category->getTranslation('name', $lang) }}"
                                    class="form-control" id="name" placeholder="Name" required>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Parent Category</label>
                            <div class="col-md-9">
                                <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2"
                                    data-placeholder="Choose ..."data-live-search="true"
                                    data-selected="{{ $category->parent_id }}">
                                    <option value="0">No Parent</option>
                                    @foreach ($categories as $acategory)
                                        <option value="{{ $acategory->id }}">{{ $acategory->name }}
                                        </option>
                                        @foreach ($acategory->childrenCategories as $childCategory)
                                            @include('categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">
                                Ordering Number
                            </label>
                            <div class="col-md-9">
                                <input type="number" name="order_level" value="{{ $category->order_level }}"
                                    class="form-control" id="order_level" placeholder="Order Level">
                                <small>Higher number has high priority</small>
                            </div>
                        </div>

                        @livewire('slug-check', ['model' => 'App\\Models\\Category', 'model_id' => $category->id, 'template' => 2])
                        @error('slug')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror


                        <div class="form-group  row">
                            <label class="col-md-3 col-form-label">Is Featured</label>
                            <div class="col-md-9">
                                <select class="select2 form-control" name="featured">
                                    <option {{ old('featured', $category->featured) == 1 ? 'selected' : '' }}
                                        value="1">Yes
                                    </option>
                                    <option {{ old('featured', $category->featured) == 0 ? 'selected' : '' }}
                                        value="0">No
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group  row">
                            <label class="col-md-3 col-form-label">Is Top</label>
                            <div class="col-md-9">
                                <select class="select2 form-control" name="top">
                                    <option {{ old('top', $category->top) == 1 ? 'selected' : '' }} value="1">Yes
                                    </option>
                                    <option {{ old('top', $category->top) == 0 ? 'selected' : '' }} value="0">No
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">Banner
                                <small>(200x200)</small></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input type="hidden" name="banner" class="selected-files"
                                        value="{{ $category->banner }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">Icon
                                <small>(32x32)</small></label>
                            <div class="col-md-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input type="hidden" name="icon" class="selected-files"
                                        value="{{ $category->icon }}">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Meta Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="meta_title"
                                    placeholder="Meta Title"
                                    value="{{ old('meta_title', $category->meta_title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Meta Description</label>
                            <div class="col-md-9">
                                <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description', $category->meta_description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Meta Keywords</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="meta_keywords"
                                    placeholder="Meta Keywords"
                                    value="{{ old('meta_keywords', $category->meta_keywords) }}">
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">OG Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="og_title"
                                    placeholder="OG Title" value="{{ old('og_title', $category->og_title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">OG Description</label>
                            <div class="col-md-9">
                                <textarea name="og_description" rows="5" class="form-control">{{ old('og_description', $category->og_description) }}</textarea>
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="name">Twitter Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="twitter_title"
                                    placeholder="Twitter Title"
                                    value="{{ old('twitter_title', $category->twitter_title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="name">Twitter Description</label>
                            <div class="col-md-9">
                                <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description', $category->twitter_description) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="name">Footer Title</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="footer_title"
                                    placeholder="Footer Title" value="{{ old('footer_title', $category->footer_title) }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                for="name">Footer Description</label>
                            <div class="col-md-9">
                                <textarea name="footer_description" rows="5" class="form-control aiz-text-editor">{{ old('footer_description', $category->footer_content) }}</textarea>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-form-label">Filtering Attributes</label>
                            <div class="col-md-9">
                                <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]"
                                    data-toggle="select2" data-placeholder="Choose ..."data-live-search="true"
                                    data-selected="{{ $category->attributes->pluck('id') }}" multiple>
                                    @foreach (\App\Models\Attribute::all() as $attribute)
                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @livewireScripts
    @livewireStyles
@endsection
