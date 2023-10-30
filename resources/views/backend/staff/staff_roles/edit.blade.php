@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Role Information') }}</h5>
    </div>


    <div class="col-lg-7 mx-auto">
        <div class="card">
            <div class="card-body p-0">

                <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label" for="name">{{ translate('Name') }} <i
                                class="las la-language text-danger" title="{{ translate('Translatable') }}"></i></label>
                        <div class="col-md-9">
                            <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name"
                                class="form-control" value="{{ $role->getTranslation('name') }}" required>
                        </div>
                    </div>
                    <div class="card-header">
                        <h5 class="mb-0 h6">Permissions</h5>
                    </div>
                    <br>
                    @php
                        $permissions = json_decode($role->permissions);
                    @endphp
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label" for="banner"></label>
                        <div class="col-md-8">

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products View</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="2" {{ in_array(2, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Create</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="25" {{ in_array(25, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Edit</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="26" {{ in_array(26, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Delete</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="27" {{ in_array(27, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Import</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="30" {{ in_array(30, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Products Export</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="31" {{ in_array(31, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">All Orders</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="3" {{ in_array(3, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Product Enquiry</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="28" {{ in_array(28, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Customers</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="8" {{ in_array(8, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Reports</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="10" {{ in_array(10, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Marketing</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="11" {{ in_array(11, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Website Setup</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="13" {{ in_array(13, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Setup & Configurations</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="14" {{ in_array(14, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Staffs</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="20" {{ in_array(20, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="col-from-label">Uploaded Files</label>
                                </div>
                                <div class="col-md-2">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="permissions[]" class="form-control demo-sw"
                                            value="22" {{ in_array(22, $permissions) ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
@endsection
