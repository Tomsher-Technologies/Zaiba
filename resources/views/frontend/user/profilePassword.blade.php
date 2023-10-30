@extends('frontend.layouts.app')

@section('content')
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('dashboard') }}">My Account</a></li>
                <li>My Account</li>
            </ul>
        </div>
    </div>
    <div class="ps-section--shopping ps-shopping-cart">
        <div class="container">
            <div class="ps-section__content">
                <div class="row">
                    @include('frontend.partials.dashboard.sidebar')
                    <div class="col-xxl-8 col-lg-8">
                        <div class="dashboard-right-sidebar">
                            <div class="tab-content">
                                <div class="">
                                    <div class="dashboard-home">
                                        <div class="title">
                                            <h4>Change Password</h4>
                                        </div>
                                        <form action="{{ route('profile.password') }}" method="POST">
                                            @csrf

                                            @if (Session::has('status'))
                                                <p class="alert alert-success">{{ Session::get('status') }}</p>
                                            @endif

                                            @error('invalid')
                                                <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror

                                            <div class="ps-form__billing-info">
                                                <div class="form-group">
                                                    <label>Current Password<sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input class="form-control" name="current_password" type="password">
                                                    </div>
                                                    @error('current_password')
                                                        <span class="invalid-feedback d-block" style="font-size: 14px"
                                                            role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>New Password<sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input class="form-control" name="password" type="password">
                                                        <small>The password must be at least 6 characters and must contain
                                                            at least one number.</small>
                                                    </div>
                                                    @error('password')
                                                        <span class="invalid-feedback d-block" style="font-size: 14px"
                                                            role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Confirm Password<sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input class="form-control" name="password_confirmation"
                                                            type="password">
                                                    </div>
                                                </div>
                                                <div class="form-group submtit">
                                                    <button type="submit" class="ps-btn ps-btn--fullwidth mb-5">Update
                                                        Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
