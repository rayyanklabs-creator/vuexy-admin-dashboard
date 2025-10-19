@extends('layouts.authentication.master')
@section('title', 'Reset Password')

@section('css')
@endsection

@section('css')
@endsection

@section('content')
    <!-- Reset Password -->
    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-6">
                <a href="{{ route('login') }}" class="app-brand-link">
                    <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/default.svg') }}"></span>
                    <span class="app-brand-text demo text-heading fw-bold"> Vuexy</span>
                </a>
            </div>


            <h4 class="mb-1">{{ __('Reset Password 🔒') }}</h4>
            <p class="mb-6">{{ __('Your new password must be different from previously used passwords') }}</p>
            <form id="formAuthentication" class="mb-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                <input type="hidden" name="email" value="{{ request()->email }}">
                <div class="mb-6 form-validation form-password-toggle">
                    <label class="form-label" for="password">{{ __('New Password') }}</label><span
                        class="text-danger">*</span>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="mb-6 form-validation form-password-toggle">
                    <label class="form-label" for="password_confirmation">{{ __('Confirm New Password') }}</label><span
                        class="text-danger">*</span>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password_confirmation" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary d-grid w-100 mb-6">{{ __('Set new password') }}</button>
                <div class="text-center">
                    <a href="{{ route('login') }}">
                        <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
                        {{ __('Back to login') }}
                    </a>
                </div>
            </form>

        </div>
    </div>
    <!-- /Reset Password -->
@endsection

@section('script')

@endsection
