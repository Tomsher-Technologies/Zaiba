@extends('backend.layouts.app')
@section('content')

    <div class="row">
        <div class="col-xl-10 mx-auto">
            <h4 class="fw-600">Home Page Settings</h4>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Collections</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="heading1" value="{{ old('heading1', $page->heading1) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="sub_heading1"  value="{{ old('sub_heading1', $page->sub_heading1) }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Categories</label>
                            <div class="new_collection-categories-target">
                                <input type="hidden" name="types[]" value="new_collection_categories">
                                <input type="hidden" name="page_type" value="new_collection">
                                
                                @if (get_setting('new_collection_categories') != null && get_setting('new_collection_categories') != 'null')
                                    @foreach (json_decode(get_setting('new_collection_categories'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col">
                                                <div class="form-group">
                                                    <select class="form-control aiz-selectpicker" name="new_collection_categories[]" data-live-search="true" data-selected={{ $value }}
                                                        required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                @include('categories.child_category', [
                                                                    'child_category' => $childCategory,
                                                                ])
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='<div class="row gutters-5">
								<div class="col">
									<div class="form-group">
										<select class="form-control aiz-selectpicker" name="new_collection_categories[]" data-live-search="true" required>
                                            <option value="">Select Category</option>
											@foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                            @include('categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                            @endforeach
                                            @endforeach
										</select>
									</div>
								</div>
								<div class="col-auto">
									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
										<i class="las la-times"></i>
									</button>
								</div>
							</div>'
                                data-target=".new_collection-categories-target">
                                Add New
                            </button>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Banner 1 --}}

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Home New Collection Banners</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="home_banner">
                        <input type="hidden" name="name" value="home_banner">
                        <input type="hidden" name="page_type" value="home_banner">
                        @error('home_banner')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- <div class="form-group">
                            <label>Status</label>
                            <div class="home-banner1-target">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="status"
                                        {{ get_setting('home_banner_status') == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div> --}}
                        @php
                            $small_banners = json_decode($current_banners['home_banner']->value);
                        @endphp
                        <div class="form-group">
                            <label>Banner 1</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[0]) && $banner->id == $small_banners[0] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 2</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[1]) && $banner->id == $small_banners[1] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Banner 3</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[2]) && $banner->id == $small_banners[2] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Banner 4</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true">
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($small_banners[3]) && $banner->id == $small_banners[3] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

             {{-- Home categories --}}
             <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Trending Categories</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="trend_title" value="{{ old('trend_title', $page->heading2) }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="trend_sub_title"  value="{{ old('trend_sub_title', $page->sub_heading2) }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Categories</label>
                            <div class="home-categories-target">
                                <input type="hidden" name="types[]" value="home_categories">
                                <input type="hidden" name="page_type" value="trending_categories">
                                
                                @if (get_setting('home_categories') != null && get_setting('home_categories') != 'null') 
                                    @foreach (json_decode(get_setting('home_categories'), true) as $key => $value)
                                        <div class="row gutters-5">
                                            <div class="col">
                                                <div class="form-group">
                                                    <select class="form-control aiz-selectpicker" name="home_categories[]"
                                                        data-live-search="true" data-selected={{ $value }}
                                                        required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}
                                                            </option>
                                                            @foreach ($category->childrenCategories as $childCategory)
                                                                @include('categories.child_category', [
                                                                    'child_category' => $childCategory,
                                                                ])
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                data-content='<div class="row gutters-5">
								<div class="col">
									<div class="form-group">
										<select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" required>
                                            <option value="">Select Category</option>
											@foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @foreach ($category->childrenCategories as $childCategory)
                                            @include('categories.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                            @endforeach
                                            @endforeach
										</select>
									</div>
								</div>
								<div class="col-auto">
									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
										<i class="las la-times"></i>
									</button>
								</div>
							</div>'
                                data-target=".home-categories-target">
                                Add New
                            </button>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Trending Products</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="trend_prod_title" value="{{ old('trend_prod_title', $page->heading3) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="trend_prod_sub_title"  value="{{ old('trend_prod_sub_title', $page->sub_heading3) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-from-label">{{ translate('Products') }}</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="trending_products">
                                <input type="hidden" name="page_type" value="trending_products">
                                <select name="trending_products[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" data-selected="{{ get_setting('trending_products') }}">
                                    <option value="">Select Products</option>
                                    @foreach ($products as $key => $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-md-2 col-from-label">{{ translate('Best Selling') }}</label>
                            <div class="col-md-10">
                                <input type="hidden" name="types[]" value="best_selling">
                                <select name="best_selling[]" class="form-control aiz-selectpicker" multiple
                                    data-live-search="true" data-selected="{{ get_setting('best_selling') }}">
                                    @foreach ($products as $key => $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Highlights Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="heading4" value="{{ old('heading4', $page->heading4) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="sub_heading4"  value="{{ old('sub_heading4', $page->sub_heading4) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h6 class="mb-0">Customers Count Section</h6>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 
                                <small>(65x65)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image1', $page->image1) }}" type="hidden" name="image1" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image1')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Customers Count') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Count') }}" name="heading5" value="{{ old('heading5', $page->heading5) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="sub_heading5"  value="{{ old('sub_heading5', $page->sub_heading5) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h6 class="mb-0">Outlets Count Section</h6>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 
                                <small>(65x65)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image2', $page->image2) }}" type="hidden" name="image2" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image2')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Outlets Count') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Count') }}" name="heading6" value="{{ old('heading6', $page->heading6) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="sub_heading6"  value="{{ old('sub_heading6', $page->sub_heading6) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <h6 class="mb-0">Highlight Points</h6>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 1') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 1') }}" name="title1" value="{{ old('title1', $page->title1) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 1 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image3', $page->image3) }}" type="hidden" name="image3" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image3')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 2') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 2') }}" name="title2" value="{{ old('title2', $page->title2) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 2 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image4', $page->image4) }}" type="hidden" name="image4" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image4')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 3') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 3') }}" name="title3" value="{{ old('title3', $page->title3) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 3 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image5', $page->image5) }}" type="hidden" name="image5" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image5')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 4') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 4') }}" name="title4" value="{{ old('title4', $page->title4) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 4 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image6', $page->image6) }}" type="hidden" name="image6" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image6')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 5') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 5') }}" name="title5" value="{{ old('title5', $page->title5) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 5 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image7', $page->image7) }}" type="hidden" name="image7" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image7')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title 6') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title 6') }}" name="title6" value="{{ old('title6', $page->title6) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Icon 6 
                                <small>(107x107)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image8', $page->image8) }}" type="hidden" name="image8" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                @error('image8')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                       
                        <div class="text-right">
                            <input type="hidden" name="page_type" value="highlights_section">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mid Section Banners</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="home_banner">
                        <input type="hidden" name="name" value="home_mid_banner">

                        @error('home_mid_banner')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        @php
                            $mid_banner = (isset($current_banners['home_mid_banner'])) ? json_decode($current_banners['home_mid_banner']->value) : [];
                        @endphp

                        {{-- <div class="form-group">
                            <label>Status</label>
                            <div class="home-banner1-target">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" name="status"
                                        {{ get_setting('home_mid_banner_status') == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label>Banner 1</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_banner[0]) && $banner->id == $mid_banner[0] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 2</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_banner[1]) && $banner->id == $mid_banner[1] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Banner 3</label>
                            <div class="home-banner1-target">
                                @if ($banners)
                                    <select class="form-control aiz-selectpicker" name="banner[]" data-live-search="true" required>
                                        <option value="">Select Banner</option>
                                        @foreach ($banners as $banner)
                                            <option value="{{ $banner->id }}"
                                                {{ isset($mid_banner[2]) && $banner->id == $mid_banner[2] ? 'selected' : '' }}>
                                                {{ $banner->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Home Banner 3 --}}

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">About Us Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_type" value="home_about">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="heading7" value="{{ old('heading7', $page->heading7) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="sub_heading7"  value="{{ old('sub_heading7', $page->sub_heading7) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Description') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="5" name="description" required>{{ old('description', $page->description) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 1
                                <small>(440x406)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image9', $page->image9) }}" type="hidden" name="image9" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 2
                                <small>(440x406)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image10', $page->image10) }}" type="hidden" name="image10" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Newsletter Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_type" value="home_newsletter">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="heading8" value="{{ old('heading8', $page->heading8) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="sub_heading8"  value="{{ old('sub_heading8', $page->sub_heading8) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Content') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="3" name="content8" required>{{ old('Content', $page->content8) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Background Image
                                <small>(1920x431)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image11', $page->image11) }}" type="hidden" name="image11" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Get Inspired Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="page_type" value="home_footer">

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="heading9" value="{{ old('heading9', $page->heading9) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                    class="text-danger">*</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="sub_heading9"  value="{{ old('sub_heading9', $page->sub_heading9) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 1
                                <small>(345x345)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image12', $page->image12) }}" type="hidden" name="image12" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 2
                                <small>(345x345)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image13', $page->image13) }}" type="hidden" name="image13" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 3
                                <small>(345x345)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image14', $page->image14) }}" type="hidden" name="image14" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="signinSrEmail">
                                Image 4
                                <small>(345x345)</small>
                            </label>
                            <div class="col-md-10">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">
                                            Browse
                                        </div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input value="{{ old('image15', $page->image15) }}" type="hidden" name="image15" class="selected-files"
                                        required>
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>


                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Footer Points Section</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="types[]" value="home_footer_points">

                        @for ($i=0; $i<4; $i++)

                            @php
                                $points = (get_setting('home_footer_point_'.$i+1) != 'null' && get_setting('home_footer_point_'.$i+1) != null) ? json_decode(get_setting('home_footer_point_'.$i+1), true) : [];
                            @endphp
                            <div class="form-group row">
                                <h6 class="ml-3">Point {{$i+1}}</h6>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="points[{{$i}}][title]" value="{!! $points['title']  ?? '' !!}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-from-label" for="name">{{ translate('Sub Title') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="{{ translate('Sub Title') }}" name="points[{{$i}}][sub_title]"  value="{!! $points['sub_title'] ?? '' !!}" required>
                                </div>
                            </div>
                       @endfor
                       
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card">


                <form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
        
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
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            AIZ.plugins.bootstrapSelect('refresh');
        });
    </script>
@endsection
