@extends('layouts.authentication.master')
@section('title', 'Login')

@section('css')
@endsection

@section('content')
    <!-- Login Card -->
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
                <a href="{{ route('login') }}" class="app-brand-link">
                    <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/default.svg') }}"></span>
                    <span class="app-brand-text demo text-heading fw-bold"> Vuexy</span>
                </a>
            </div>
            <!--End Logo -->
            <h4 class="mb-1">{{ __('Welcome to') }} Vuexy! 👋</h4>
            <p class="mb-6">{{ __('Please sign-in to your account and start the adventure') }}</p>


            <form id="formLogin" class="mb-4" action="{{ route('login.attempt') }}" method="POST">
                @csrf
                <div class="mb-6 form-control-validation">
                    <label for="email_username" class="form-label">{{ __('Email/Username') }}</label><span
                        class="text-danger">*</span>
                    <input type="text" class="form-control @error('email_username') is-invalid @enderror"
                        value="{{ old('email_username') }}" id="email_username" name="email_username"
                        placeholder="{{ __('Enter your email or username') }}" autofocus required />
                    @error('email_username')
                        <strong class="invalid-feedback">{{ $message }}</strong>
                        <span class="invalid-feedback" role="alert">
                        </span>
                    @enderror
                </div>
                <div class="mb-6 form-control-validation form-password-toggle">
                    <label class="form-label" for="password">{{ __('Password') }}</label><span class="text-danger">*</span>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="my-8">
                    <div class="d-flex justify-content-between">
                        <div class="form-check mb-0 ms-2">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember-me" />
                            <label class="form-check-label" for="remember-me"> {{ __('Remember Me') }} </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                <p class="mb-0">{{ __('Forgot Password?') }}</p>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="mb-6">
                    <button type="submit" class="btn btn-primary d-grid w-100">{{ __('Sign in') }}</button>
                </div>
            </form>
            <p class="text-center">
                <span>{{ __('New on our platform?') }}</span>
                <a href="{{ route('register') }}">
                    <span>{{ __('Create an account') }}</span>
                </a>
            </p>

            <div class="divider my-6">
                <div class="divider-text">{{ __('or') }}</div>
            </div>

            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-sm btn-icon rounded-pill btn-text-google-plus">
                    <i class="tf-icons ti ti-brand-google-filled"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- End Login Card -->
@endsection

@section('script')
@endsection
