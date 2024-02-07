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

        <form class="p-4 repeater" action="{{ route('custom-pages.update', $page->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH">

            <div class="card-header px-0">
                <h6 class="fw-600 mb-0">Page Content</h6>
            </div>
            <div class="card-body px-0">
                <input type="hidden" name="type" value="{{ $page->type }}">

                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Title') }} <span
                            class="text-danger">*</span> </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="title"
                            value="{{ $page->title }}">
                            <input type="hidden" name="type" value="{{ $page->type }}">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="name">{{ translate('Image') }}</label>
                    <div class="col-sm-10">
                        <div class="input-group " data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                            </div>
                            <div class="form-control file-amount">Choose File</div>
                            <input type="hidden" name="page_image" class="selected-files"
                                value="{{ $page->image1 }}">
                        </div>
                        <div class="file-preview  box">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <h6 class='fw-600 mb-0 ml-3'> Question and Answers</h6>
                </div>

                <div data-repeater-list="faq">
                    <div data-repeater-item >
                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="question">{{ translate('Question') }} <span
                                class="text-danger">*</span> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Question') }}" name="question"
                                    value="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="answer">{{ translate('Answer') }} <span
                                class="text-danger">*</span> </label>
                            <div class="col-sm-10">
                                <textarea class="form-control" placeholder="{{ translate('Answer') }}" name="answer"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-from-label" for="sort_order">{{ translate('Sort Order') }} <span
                                class="text-danger">*</span> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="{{ translate('Sort Order') }}" name="sort_order" value="0">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="text-right col-sm-12 mb-10 d-block">
                                <input data-repeater-delete class="btn btn-danger d-initial" type="button" value="Delete" />
                            </div>
                        </div>
                        
                       
                    </div>
                </div>

                <div class=" px-sm-40 px-20 col-sm-12 mb-10">
                    <input data-repeater-create class="btn btn-success my-3" type="button" value="Add New" />
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
<script src="{{ asset('admin_assets/assets/js/jquery.repeater.min.js') }}"></script>
<script>
    let repCount = 0;
    var repeater =   $('.repeater').repeater({
        initEmpty: true,
        show: function(e) {
            $(this).slideDown();
        },
        hide: function(deleteElement) {
            if (confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            }
        },
        isFirstItemUndeletable: false
    })

    var additionals = {!! $questions !!};
    repeater.setList(additionals);

    
</script>
@endsection
