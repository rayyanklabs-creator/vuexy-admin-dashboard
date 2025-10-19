@extends('layouts.authentication.master')
@section('title', 'Forgot Password')

@section('css')
@endsection


@section('content')

    <!-- Forgot Password -->
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-6">
                <a href="{{ route('login') }}" class="app-brand-link">
                    <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/default.svg') }}"></span>
                    <span class="app-brand-text demo text-heading fw-bold"> Vuexy</span>
                </a>
            </div>

            <h4 class="mb-1">{{ __('Forgot Password? 🔒') }}</h4>
            <p class="mb-6">{{ __("Enter your email and we'll send you instructions to reset your password") }}</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label for="email" class="form-label">{{ __('Email') }}</label><span class="text-danger">*</span>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="{{ __('Enter your email') }}" autofocus />
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100">{{ __('Send Reset Link') }}</button>
            </form>
            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
                    {{ __('Back to login') }}
                </a>
            </div>
        </div>
    </div>
    <!-- /Forgot Password -->
@endsection

@section('script')
@endsection
