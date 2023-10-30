@extends('frontend.layouts.app')

@section('content')
    <div class="ps-page--my-account">

        <div class="ps-my-account"
            style="
        background-image: url({{ frontendAsset('img/banner/login-01.webp') }});
        background-size: cover;
        background-repeat: no-repeat;
      ">
            <div class="container" style="padding-top: 200px;">

                <div class="row justify-content-center  ">
                    <div class="col-lg-5">
                        <div class="auth-card mx-lg-3 ">
                            <div class="card border-0 mb-0 ">
                                <div class="card-header bg-primary border-0">
                                    <div class="row">

                                        <div class="col-lg-12 col-12">
                                            <h3 class="text-white text-center lh-base fw-lighter pt-2">Forgot Password?</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-5 pb-5">

                                    <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                                        Enter your email and instructions will be sent to you!
                                    </div>
                                    <div class="p-2">
                                        <form method="POST" action="{{ route('password.email') }}">
                                            @csrf
                                            <div class="mb-4">
                                                {{-- <label for="email" class="form-label">Email</label> --}}
                                                <input type="email" name="email" class="form-control" id="email"
                                                    placeholder="Enter your email" value="{{ old('email') }}">
                                                @error('email')
                                                    <span class="invalid-feedback d-block" style="font-size: 14px"
                                                        role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @if (session()->has('status'))
                                                    <div class="alert alert-success">
                                                        {{ session()->get('status') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="text-center mt-4">
                                                <button class="ps-btn ps-btn--fullwidth" type="submit">
                                                    Send Password Reset Link
                                                </button>
                                            </div>
                                        </form><!-- end form -->
                                    </div>
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Wait, I remember my password... <a
                                                href="{{ route('user.login') }}"
                                                class="fw-semibold text-primary text-decoration-underline"> Click here </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <h1 class="h3 fw-600">Forgot Password?</h1>
                        <p class="mb-4 opacity-60">{{ translate('Enter your email address to recover your password.') }}
                        </p>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                @if (addon_is_activated('otp_system'))
                                    <input id="email" type="text"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                        value="{{ old('email') }}" required placeholder="Email or Phone">
                                @else
                                    <input type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        value="{{ old('email') }}" placeholder="Email" name="email">
                                @endif

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary btn-block" type="submit">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <a href="{{ route('user.login') }}"
                                class="text-reset opacity-60">{{ translate('Back to Login') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
