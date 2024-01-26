@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">Add New Product</h5>
    </div>
    <div class="">
        <form class="form form-horizontal mar-top" id="addNewProduct" action="{{ route('products.store') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                
                                <label class="col-md-3 col-from-label">Product Name <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="name" placeholder="Product Name"
                                        onchange="title_update(this)" required>
                                </div>
                            </div>
                            <div class="form-group row" id="category">
                                <label class="col-md-3 col-from-label">Category <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id"
                                        data-live-search="true" required>
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
                            <div class="form-group row" id="design">
                                <label class="col-md-3 col-from-label">Design</label>
                                <div class="col-md-8">
                                    @php   
                                        $designs = \App\Models\Designs::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="design_id" id="design_id"
                                        data-live-search="true">
                                        <option value="">Select Design</option>
                                        @foreach ($designs as $design)
                                            <option value="{{ $design->id }}">{{ $design->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="design_category">
                                <label class="col-md-3 col-from-label">Design Category</label>
                                <div class="col-md-8">
                                    @php   
                                        $designCategories = \App\Models\DesignCategories::where('is_active',1)->orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control" name="design_category_id" id="design_category_id"
                                        data-live-search="true">
                                        <option value="">Select Design Category</option>
                                        @foreach ($designCategories as $designCats)
                                            <option value="{{ $designCats->type }}">{{ $designCats->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-md-3 col-from-label">Unit</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="unit"
                                        placeholder="Unit (e.g. KG, Pc etc)" required>
                                </div>
                            </div> -->

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Metal Type</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="metal_type" placeholder="Metal Type (e.g. Yellow gold)" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Purity <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="purity" placeholder="Purity (e.g. 24, 22, 18)" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Minimum Purchase Qty <span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="number" lang="en" class="form-control" name="min_qty" value="1"
                                        min="1" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Tags</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="Type and hit enter to add a tag">
                                    <small class="text-muted">This is used for search. Input those words by which cutomer
                                        can find this product.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Slug<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="Slug" id="slug" name="slug" required
                                        class="form-control">
                                    @error('slug')
                                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Images</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">Gallery
                                    Images<small>(1000*1000)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="gallery_images[]" multiple class="form-control"
                                        accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="signinSrEmail">Thumbnail Image
                                    <small>(1000*1000)</small></label>
                                <div class="col-md-8">
                                    <input type="file" name="thumbnail_image" class="form-control" accept="image/*" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Discounts</h5>
                        </div>
                        <div class="card-body">
                           
                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="date_range">Discount Date Range</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control aiz-date-range" id="date_range"
                                        name="date_range" placeholder="Select Date" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Discount</label>
                                <div class="col-md-6">
                                    <input type="number" lang="en" min="0" value="0" step="0.01"
                                        placeholder="Discount" name="discount" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control aiz-selectpicker" name="discount_type">
                                        <option value="amount">Flat</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    @php   
                        $makingPriceTypes = \App\Models\MakingPriceTypes::where('is_active',1)->orderBy('name','asc')->get();
                        $making_type = '';
                        foreach ($makingPriceTypes as $makingType){
                            $making_type .= '<option value="'.$makingType->id.'">'.$makingType->name.'</option>';
                        }
                    @endphp
                                            
                    <div class="card product-repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Details</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Product Type <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select class="form-control aiz-selectpicker" name="product_type" id="product_type" required>
                                        <option value="single">Single</option>
                                        <option value="variant">Variants</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="attributes">
                                <input type="hidden" name="selected_attributes" id="selected_attributes">
                                <label class="col-md-3 col-from-label">Attributes <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    @php   
                                        $attributes = \App\Models\Attribute::orderBy('name','asc')->get();
                                    @endphp
                                    <select class="form-control aiz-selectpicker" name="main_attributes[]" multiple id="main_attributes"  data-live-search="true">
                                        <option value="">Select Attributes</option>
                                        @foreach ($attributes as $attr)
                                            <option value="{{ $attr->id }}">{{ $attr->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div data-repeater-list="products">
                                <div data-repeater-item>
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h6 class="pro_variant_name" id="pro_variant_name">Product Variant 1</h6>
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">SKU <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="text" placeholder="SKU" name="sku" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row imageVariant">
                                        <label class="col-md-3 col-form-label" for="signinSrEmail">Product Variant
                                            Image<small>(1000*1000)</small></label>
                                        <div class="col-md-8">
                                            <input type="file" name="variant_images" class="form-control variant_images"
                                                accept="image/*" required>
                                        </div>
                                    </div>

                                    <div class="product_attributes" >
                                        
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Description <span class="text-danger">*</span></label>
                                        <div class="col-md-8">
                                            <textarea class="description-text-area" name="description" ></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Quantity <span  class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="Quantity" name="current_stock" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Metal Weight<span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01"
                                                placeholder="Metal Weight" name="metal_weight" class="form-control" required>
                                        </div>
                                    </div>
                                
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Stone Available</label>
                                        <div class="col-md-6">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input type="checkbox"  class="stone_availability" name="stone_availability" id="stone_availability" value="" >
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="stone-div">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-from-label"> Stone Type</label>
                                            <div class="col-md-6">
                                                <input type="text" placeholder="Stone Type" name="stone_type" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-from-label">Stone Count</label>
                                            <div class="col-md-6">
                                                <input type="number" lang="en" min="0" value="0" step="1" placeholder="Stone Count" name="stone_count" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-from-label">Stone Weight</label>
                                            <div class="col-md-6">
                                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="Stone Weight" name="stone_weight" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-from-label">Stone Price</label>
                                            <div class="col-md-6">
                                                <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="Stone Price" name="stone_price" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="">
                                        <label class="col-md-3 col-from-label">Making Price Type <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            
                                            <select class="form-control" name="making_price_type_id" id="making_price_type_id" data-live-search="true" required>
                                                <option value="">Select Making Price Type</option>
                                                {!! $making_type !!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Making Charge <span class="text-danger">*</span></label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="Making Charge" name="making_charge" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <input data-repeater-delete type="button" class="btn btn-danger action-btn" value="Delete" />
                                        </div>
                                    </div>     
                                </div>
                            </div>
                            <div class="form-group row add_variant" >
                                <div class="col-md-12">
                                    <input data-repeater-create type="button" class="btn btn-success action-btn" value="Add Product Variant" />
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card repeater">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Tabs</h5>
                        </div>
                        <div class="card-body">
                            <div data-repeater-list="tabs">
                                <div data-repeater-item>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Heading</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="tab_heading">
                                        </div>
                                        <input data-repeater-delete type="button" class="btn btn-danger action-btn"
                                            value="Delete" />
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">Description</label>
                                        <div class="col-md-8">
                                            <textarea class="text-area" name="tab_description"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success action-btn"
                                value="Add" />
                        </div>
                    </div>

                    

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Product Videos</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Video Provider</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube">Youtube</option>
                                        <option value="dailymotion">Dailymotion</option>
                                        <option value="vimeo">Vimeo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Video Link</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="Video Link">
                                    <small
                                        class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">SEO Meta Tags</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">Meta Title</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        placeholder="Meta Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">Description</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">Keywords</label>
                                <div class="col-md-8">
                                    {{-- data-max-tags="1" --}}
                                    <input type="text" class="form-control aiz-tag-input" name="meta_keywords[]"
                                        placeholder="Type and hit enter to add a keyword">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">OG Title</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="og_title" placeholder="OG Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">OG Description</label>
                                <div class="col-lg-8">
                                    <textarea name="og_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">Twitter Title</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="twitter_title"
                                        placeholder="Twitter Title">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">Twitter Description</label>
                                <div class="col-lg-8">
                                    <textarea name="twitter_description" rows="8" class="form-control"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="card bg-transparent shadow-none border-0">
                        <div class="card-body p-0">
                            <div class="btn-toolbar justify-content-end" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <div class="btn-group mr-2" role="group" aria-label="First group">
                                    <button type="submit" name="button" value="draft"
                                        class="btn btn-warning action-btn">Save As Draft</button>
                                </div>
                                <!-- <div class="btn-group mr-2" role="group" aria-label="Third group">
                                    <button type="submit" name="button" value="unpublish"
                                        class="btn btn-primary action-btn">Save & Unpublish</button>
                                </div> -->
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <button type="submit" name="button" value="publish"
                                        class="btn btn-success action-btn">Save & Publish</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Price visibility</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Hide Price</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="hide_price" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Low Stock Quantity Warning</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="low_stock_quantity">
                                    Quantity
                                </label>
                                <input type="number" name="low_stock_quantity" id="low_stock_quantity" value="1"
                                    min="0" step="1" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">
                                Stock Visibility State
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Show Stock Quantity</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="quantity" checked>
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Show Stock With Text Only</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="text">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Hide Stock</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="radio" name="stock_visibility_state" value="hide">
                                        <span></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Featured</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Status</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="featured" value="1">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">Return and refund status</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">Status</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="return_refund" value="0">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group mr-2" role="group" aria-label="First group">
                            <button type="submit" name="button" value="draft" class="btn btn-warning action-btn">Save
                                As Draft</button>
                        </div>
                        <!-- <div class="btn-group mr-2" role="group" aria-label="Third group">
                            <button type="submit" name="button" value="unpublish"
                                class="btn btn-primary action-btn">Save & Unpublish</button>
                        </div> -->
                        <div class="btn-group" role="group" aria-label="Second group">
                            <button type="submit" name="button" value="publish"
                                class="btn btn-success action-btn">Save & Publish</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('styles')
<style>
    .pro_variant_name{
        text-decoration: underline;
        text-underline-position: under;
    }
</style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
        integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.stone-div,.imageVariant').hide();

        $('.variant_images').removeAttr('required');
        $('.add_variant,#attributes,.pro_variant_name').hide();
        let buttons = [
                    ["font", ["bold", "underline", "italic", "clear"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["style", ["style"]],
                    ["color", ["color"]],
                    ["table", ["table"]],
                    ["insert", ["link", "picture", "video"]],
                    ["view", ["fullscreen", "undo", "redo"]],
                ];
        $('.description-text-area').summernote({
            toolbar: buttons,
            height: 200,
            callbacks: {
                onImageUpload: function(data) {
                    data.pop();
                },
                onPaste: function(e) {
                    if (format) {
                        var bufferText = ((e.originalEvent || e).clipboardData || window
                            .clipboardData).getData('Text');
                        e.preventDefault();
                        document.execCommand('insertText', false, bufferText);
                    }
                }
            }
        });



        $('.repeater').repeater({
            initEmpty: true,
            show: function() {
                $(this).slideDown();

                note = $(this).find('.text-area').summernote({
                    toolbar: buttons,
                    height: 200,
                    callbacks: {
                        onImageUpload: function(data) {
                            data.pop();
                        },
                        onPaste: function(e) {
                            if (format) {
                                var bufferText = ((e.originalEvent || e).clipboardData || window
                                    .clipboardData).getData('Text');
                                e.preventDefault();
                                document.execCommand('insertText', false, bufferText);
                            }
                        }
                    }
                });

                var nativeHtmlBuilderFunc = note.summernote('module', 'videoDialog').createVideoNode;

                note.summernote('module', 'videoDialog').createVideoNode = function(url) {
                    var wrap = $('<div class="embed-responsive embed-responsive-16by9"></div>');
                    var html = nativeHtmlBuilderFunc(url);
                    html = $(html).addClass('embed-responsive-item');
                    return wrap.append(html)[0];
                };

            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

        $('.product-repeater').repeater({
            initEmpty: false,
            isFirstItemUndeletable: true,
            show: function() {
                $(this).slideDown();

                var repeaterItems = $("div[data-repeater-item]");
                var repeatCount = repeaterItems.length;
                var count = parseInt(repeatCount) - 1;

                $('[name="products['+count+'][sku]"]').parent().parent().parent().find('#pro_variant_name').attr("id","pro_variant_name"+count);

                $('#pro_variant_name'+count).html('Product Variant '+repeatCount);
                $('.pro_variant_name').show();
                $('.imageVariant').show();
                // $('.variant_images').addAttr('required');
                $('.variant_images').prop('required', true);
                $.each($("#main_attributes option:selected"), function() {
                    var i = $(this).val();
                    var name = $(this).text();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type:"POST",
                        url:'{{ route('products.add-attributes') }}',
                        data:{
                        attribute_id: i
                        },
                        success: function(data) {
                            var obj = JSON.parse(data);
                           
                            $('[name="products['+count+'][variant_images]"]').parent().parent().parent().find(".product_attributes").first().append('\
                                <div class="form-group row">\
                                    <div class="col-md-3">\
                                        <input type="text" class="form-control" name="products['+count+'][choice_'+ i +']" value="'+name+'" placeholder="Choice Title" readonly>\
                                    </div>\
                                    <div class="col-md-8">\
                                        <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="products['+count+'][choice_options_'+ i +']">\
                                            '+obj+'\
                                        </select>\
                                    </div>\
                                </div>');
                            
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                    });
                });

           
                $('[name="products['+count+'][stone_availability][]"]').attr("id","stone_availability"+count);
                $("#stone_availability"+count).prop('checked',false);
                
                $(this).find('.note-editor').remove();
                note = $(this).find('.description-text-area').summernote({
                    toolbar: buttons,
                    height: 200,
                    callbacks: {
                        onImageUpload: function(data) {
                            data.pop();
                        },
                        onPaste: function(e) {
                            if (format) {
                                var bufferText = ((e.originalEvent || e).clipboardData || window
                                    .clipboardData).getData('Text');
                                e.preventDefault();
                                document.execCommand('insertText', false, bufferText);
                            }
                        }
                    }
                });
            },
            hide: function(deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                }
            },
        });

        $(document).on('change','.stone_availability',function(){
            if($(this).prop('checked') == true){
                $(this).parent().parent().parent().parent().find(".stone-div").first().show();
            }else{ 
                $(this).parent().parent().parent().parent().find(".stone-div").first().hide();
            }
        });

        $(document).on('change','#product_type',function(){
            if($(this).val() == 'variant'){
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').show();
                $('.variant_images').prop('required', true);
            }else{ 
                $('.add_variant,#attributes,.pro_variant_name,.imageVariant').hide();
                $('.variant_images').removeAttr('required');
                $('div[data-repeater-item]').slice(1).remove();
            }
        });

        let selected_attributes = [];
        $('#main_attributes').on('change', function() {
             $('.product_attributes').html(null);
            $.each($("#main_attributes option:selected"), function() {
                 add_more_customer_choice_option($(this).val(), $(this).text());
                 if( $.inArray($(this).val(), selected_attributes) == -1 ) {
                     selected_attributes.push($(this).val());
                 }
                 $('#selected_attributes').val(selected_attributes);
             });

           // const values = $(this).val();
           // // Remove all non selected from selected array if user has deselected something
          //  selected = selected.filter((value) => values.includes(value));
          //  // get value which is not in selected list
           // const lastSelected = values.filter((value) => !selected.includes(value));

        });

        function add_more_customer_choice_option(i, name){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type:"POST",
                url:'{{ route('products.add-attributes') }}',
                data:{
                attribute_id: i
                },
                success: function(data) {
                    var obj = JSON.parse(data);

                    // if( $.inArray(i, selected_attributes) !== -1 ) {
                        $('.product_attributes').append('\
                            <div class="form-group row">\
                                <div class="col-md-3">\
                                    <input type="text" class="form-control" name="choice_'+ i +'" value="'+name+'" placeholder="Choice Title" readonly>\
                                </div>\
                                <div class="col-md-8">\
                                    <select required class="form-control aiz-selectpicker attribute_choice" data-live-search="true" name="choice_options_'+ i +'">\
                                        '+obj+'\
                                    </select>\
                                </div>\
                            </div>');
                    // }
                    AIZ.plugins.bootstrapSelect('refresh');
                }
            });
        }

        
    </script>

    <script type="text/javascript">
        $('form').bind('submit', function(e) {
            if ($(".action-btn").attr('attempted') == 'true') {
                //stop submitting the form because we have already clicked submit.
                e.preventDefault();
            } else {
                $(".action-btn").attr("attempted", 'true');
            }
            // Disable the submit button while evaluating if the form should be submitted
            // $("button[type='submit']").prop('disabled', true);

            // var valid = true;

            // if (!valid) {
            // e.preventDefault();

            ////Reactivate the button if the form was not submitted
            // $("button[type='submit']").button.prop('disabled', false);
            // }
        });

        function title_update(e) {
            title = e.value;
            title = title.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')
            $('#slug').val(title)
        }

        

        function checkStoneAvailability(checked){
            if(checked == true){
                $('#stone-div').css('display','block');
            }else{  
                $('#stone-div').css('display','none');
            }
        }

      
        
    </script>
@endsection
