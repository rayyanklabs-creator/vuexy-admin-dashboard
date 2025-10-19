@extends('layouts.authentication.master')
@section('title', 'Registration')

@section('css')
@endsection

@section('content')

    <!-- Register Card -->
    <div class="card">

        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
                <a href="{{ route('register') }}" class="app-brand-link">
                    <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/default.svg') }}"></span>
                    <span class="app-brand-text demo text-heading fw-bold"> Vuexy</span>
                </a>
            </div>
            <!--End Logo -->
            <h4 class="mb-1">{{ __('Create your Accounts') }} 🚀</h4>
            <p class="mb-6">{{ __('Make your app management easy and fun!') }}</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('register.attempt') }}" method="POST">
                @csrf
                <div class="mb-6 form-control-validation">
                    <label for="name" class="form-label">{{ __('Name') }}</label><span class="text-danger">*</span>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" id="name" name="name" placeholder="{{ __('Enter your name') }}"
                        autofocus required />
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-6 form-control-validation">
                    <label for="email" class="form-label">{{ __('Email') }}</label><span class="text-danger">*</span>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="{{ __('Enter your email') }}" required />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
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
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="mb-6 form-control-validation form-password-toggle">
                    <label class="form-label" for="confirm-password">{{ __('Confirm Password') }}</label><span
                        class="text-danger">*</span>
                    <div class="input-group input-group-merge">
                        <input type="password" id="confirm-password"
                            class="form-control @error('confirm-password') is-invalid @enderror" name="confirm-password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="confirm-password" required />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('confirm-password')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">{{ __('Sign up') }}</button>
            </form>

            <p class="text-center">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}">
                    <span>{{ __('Sign in instead') }}</span>
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
    <!-- End Register Card -->
@endsection
