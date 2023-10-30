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
                                            <h4>My Profile</h4>
                                        </div>
                                        <form action="{{ route('user.profile.update') }}" method="POST">
                                            @csrf

                                            @if (Session::has('status'))
                                                <p class="alert alert-success">{{ Session::get('status') }}</p>
                                            @endif

                                            <div class="ps-form__billing-info">
                                                <div class="form-group">
                                                    <label>Name<sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input class="form-control" name="name"
                                                            value="{{ auth()->user()->name }}" type="text">
                                                    </div>
                                                    @error('name')
                                                        <span class="invalid-feedback d-block" style="font-size: 14px"
                                                            role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Email Address<sup>*</sup>
                                                    </label>
                                                    <div class="form-group__content">
                                                        <input class="form-control" type="email"
                                                            value="{{ auth()->user()->email }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group submtit">
                                                    <a href="{{ route('profile.password') }}">Update Password</a>
                                                    <button type="submit"
                                                        class="ps-btn ps-btn--fullwidth mb-5">Update</button>
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
    </div>
@endsection
