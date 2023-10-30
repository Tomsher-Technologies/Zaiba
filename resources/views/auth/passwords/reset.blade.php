@extends('frontend.layouts.app')

@section('content')
    <div class="ps-page--my-account">

        <div class="ps-my-account"
            style="background-image: url({{ frontendAsset('img/banner/login-01.webp') }});background-size: cover;background-repeat: no-repeat;">
            <div class="container" style="padding-top: 200px;">

                <div class="row justify-content-center  ">
                    <div class="col-lg-5">
                        <div class="auth-card mx-lg-3 ">
                            <div class="card border-0 mb-0 ">
                                <div class="card-header bg-primary border-0">
                                    <div class="row">
                                        <div class="col-lg-12 col-12">
                                            <h3 class="text-white text-center lh-base fw-lighter pt-2">
                                                Create New Password
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-5 pb-5">
                                    <p class="text-muted fs-15">Your new password must be different from previous used
                                        password.</p>
                                    <div class="p-2">
                                        <form method="POST" action="{{ route('password.update') }}">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">

                                            <div class="mb-3">
                                                <label class="form-label" for="password-input">Email</label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="email" name="email" class="form-control pe-5"
                                                        placeholder="Enter your email" required=""
                                                        value="{{ $email ?? old('email') }}">
                                                </div>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup">
                                                    <input type="password" class="form-control pe-5 password-input"
                                                        placeholder="Enter password" id="password-input"
                                                        aria-describedby="passwordInput" name="password" required="">
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="confirm-password-input">Confirm
                                                    Password</label>
                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                    <input type="password" class="form-control pe-5 password-input"
                                                        placeholder="Confirm password" name="password_confirmation"
                                                        id="confirm-password-input" required="">
                                                </div>
                                            </div>

                                            <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                                                <h5 class="fs-13">Password must contain:</h5>
                                                <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>6 characters</b>
                                                </p>
                                                <p id="pass-number" class="invalid fs-12 mb-0">A least <b>1 number</b> (0-9)
                                                </p>
                                            </div>

                                            <div class="mt-4">
                                                <button class="ps-btn ps-btn--fullwidth" type="submit">Reset
                                                    Password</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
        </div>
    </div>

    <style>
        .invalid-feedback {
            display: block;
        }
    </style>
    {{-- <div class="py-6">
        <div class="container">
            <div class="row">
                <div class="col-xxl-5 col-xl-6 col-md-8 mx-auto">
                    <div class="bg-white rounded shadow-sm p-4 text-left">
                        <h1 class="h3 fw-600">Reset Password</h1>
                        <p class="mb-4 opacity-60">
                            {{ translate('Enter your email address and new password and confirm password.') }} </p>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <div class="form-group">
                                <input id="email" type="email"
                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                    value="{{ $email ?? old('email') }}" placeholder="Email" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input id="code" type="text"
                                    class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code"
                                    value="{{ $email ?? old('code') }}" placeholder="{{ translate('Code') }}" required
                                    autofocus>

                                @if ($errors->has('code'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('code') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input id="password" type="password"
                                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                                    placeholder="New Password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" placeholder="Confirm Password" required>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
