@extends('backend.layouts.layout')

@section('content')
    <div class="h-100 bg-cover bg-center py-5 d-flex align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-4 mx-auto">
                    <div class="card text-left">
                        <div class="card-body">
                            <div class="mb-5 text-center">
                                <img src="{{ static_asset('assets/img/logo.png') }}" class="mw-100 mb-4" height="40">
                                <h1 class="h3 text-primary mb-0">Welcome to Zaiba</h1>
                                <p>Login to your account.</p>
                            </div>
                            <form class="pad-hor" method="POST" role="form" action="{{ route('admin.login') }}">
                                @csrf

                                @error('login')
                                    <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="form-group">
                                    <input id="email" type="email"
                                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                        value="{{ old('email') }}" required autofocus placeholder="Email">
                                    @error('email')
                                        <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password"
                                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        name="password" required placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="row mb-2">
                                    <div class="col-sm-6">
                                        <div class="text-left">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>
                                                <span>Remember Me</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                    @if (env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                        <div class="col-sm-6">
                                            <div class="text-right">
                                                <a href="{{ route('password.request') }}"
                                                    class="text-reset fs-14">{{ translate('Forgot password ?') }}</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {!! NoCaptcha::display() !!}

                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif

                                <button type="submit" class="btn btn-primary btn-lg btn-block mt-2">
                                    Login
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('header')
    {!! NoCaptcha::renderJs() !!}
@endsection
