@extends('backend.layouts.app')
@section('title', 'Create Design')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">Create Design</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Add New Design</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('designs.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Name <span class="text-danger" style="font-size: 20px;line-height: 1;">*</span></label>
                            <input type="text" placeholder="Name" name="name"
                                class="form-control slug_title" value="{{ old('name') }}" >
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        @livewire('slug-check', ['model' => 'App\\Models\\Designs'])

                        <div class="form-group  mb-3">
                            <label>Is Featured</label>
                            <select class="select2 form-control" name="is_featured">
                                <option {{ old('is_featured') == 1 ? 'selected' : '' }} value="1">Yes
                                </option>
                                <option {{ old('is_featured') == 0 ? 'selected' : '' }} value="0">No
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">Image <span class="text-danger" style="font-size: 20px;line-height: 1;">*</span>
                                <!-- <small>(150x70)</small> -->
                            </label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" value="{{ old('image') }}" name="image" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title"
                                placeholder="Meta Title" value="{{ old('meta_title') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Meta Description</label>
                            <textarea name="meta_description" rows="5" class="form-control">{{ old('meta_description') }}</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Meta Keywords</label>
                            <input type="text" class="form-control" name="meta_keywords"
                                placeholder="Meta Keywords" value="{{ old('meta_keywords') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">OG Title</label>
                            <input type="text" class="form-control" name="og_title"
                                placeholder="OG Title" value="{{ old('og_title') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">OG Description</label>
                            <textarea name="og_description" rows="5" class="form-control">{{ old('og_description') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="name">Twitter Title</label>
                            <input type="text" class="form-control" name="twitter_title"
                                placeholder="Twitter Title" value="{{ old('twitter_title') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Twitter Description</label>
                            <textarea name="twitter_description" rows="5" class="form-control">{{ old('twitter_description') }}</textarea>
                        </div>

                        <div class="form-group mb-3 text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('designs.index') }}" class="btn btn-danger radius-50">Cancel</a>
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
    <script type="text/javascript">
        $('.slug_title').on('change', function() {
            console.log($(this).val());
            Livewire.emit('titleChanged', $(this).val())
        });
    </script>
@endsection
