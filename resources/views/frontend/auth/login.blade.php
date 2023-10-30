@extends('frontend.layouts.app')
@section('content')
    <div class="ps-page--my-account">
        <div class="ps-my-account"
            style="
        background-image: url({{ frontendAsset('img/banner/login-01.webp') }});
        background-size: cover;
        background-repeat: no-repeat;
      ">
            <div class="container">
                <div class="ps-form--account ps-tab-root">
                    <ul class="ps-tab-list">
                        <li class="{{ request()->get('register') == 0 && old('register') == 0 ? 'active' : '' }}">
                            <a class="tab-switch tab-switch-login" href="#sign-in">Login</a>
                        </li>
                        <li class="{{ request()->get('register') == 1 || old('register') == 1 ? 'active' : '' }}">
                            <a class="tab-switch tab-switch-register" href="#register">Register</a>
                        </li>
                    </ul>
                    <div class="ps-tabs">
                        <div class="ps-tab {{ request()->get('register') == 0 && old('register') == 0 ? 'active' : '' }}"
                            id="sign-in">
                            <div class="ps-form__content">
                                <h5>Log in to your account</h5>
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="register" value="0">
                                    @error('login')
                                        <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <div class="form-group">
                                        <input class="form-control" name="l_email" type="email"
                                            placeholder="Email address" value="{{ old('l_email') }}" required
                                            autocomplete="email" />
                                        @error('l_email')
                                            <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group form-forgot">
                                        <input class="form-control" type="password" name="l_password" placeholder="Password"
                                            required />
                                        <a href="{{ route('password.request') }}">Forgot?</a>
                                        @error('l_password')
                                            <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <div class="ps-checkbox">
                                            <input class="form-control" type="checkbox" id="remember-me"
                                                {{ old('remember') ? 'checked' : '' }} name="remember" />
                                            <label for="remember-me">Rememeber me</label>
                                        </div>
                                    </div>
                                    <div class="form-group submtit">
                                        <button type="submit" class="ps-btn ps-btn--fullwidth mb-5">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="ps-tab {{ request()->get('register') == 1 || old('register') == 1 ? 'active' : '' }}"
                            id="register">
                            <div class="ps-form__content">
                                <h5>Create an account</h5>

                                <form action="{{ route('register') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="register" value="1">
                                    @error('register')
                                        <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <div class="form-group">
                                        <input name="name" value="{{ old('name') }}" class="form-control"
                                            type="text" placeholder="Your Name" />
                                        @error('name')
                                            <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input name="email" value="{{ old('email') }}" class="form-control"
                                            type="email" placeholder="Email address" />
                                        @error('email')
                                            <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="password" placeholder="Password"
                                            name="password" />

                                        <div id="password-contain" class="p-3 mt-3 bg-light mb-2 rounded">
                                            <h5 class="fs-13 mb-3">Password must contain:</h5>
                                            <p id="pass-length" class="invalid fs-12 mb-1">Minimum <b>6 characters</b></p>
                                            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>1 number</b> (0-9)</p>
                                        </div>

                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block" style="font-size: 14px" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-group">
                                        <input class="form-control" type="password" placeholder="Confirm Password"
                                            name="password_confirmation" />
                                    </div>
                                    <div class="form-group submtit">
                                        <button type="submit" class="ps-btn ps-btn--fullwidth mb-5">Register</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.tab-switch').on('click', function() {
            if ($(this).hasClass('tab-switch-login')) {
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname +
                    '?register=0';
                window.history.pushState({
                    path: newurl
                }, '', newurl);
            } else {
                var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname +
                    '?register=1';
                window.history.pushState({
                    path: newurl
                }, '', newurl);
            }
        });
    </script>
@endsection
