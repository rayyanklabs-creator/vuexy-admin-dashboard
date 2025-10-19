@extends('layouts.authentication.master')
@section('title', 'Verify Email')

@section('css')
@endsection

@section('content')
    <!--  Verify email -->

    <div class="card">
        <div class="card-body">
            <div class="app-brand justify-content-center mb-6">
                <a href="{{ route('login') }}" class="app-brand-link">
                    <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo/default.svg') }}"></span>
                    <span class="app-brand-text demo text-heading fw-bold"> Vuexy</span>
                </a>
            </div>
            <h4 class="mb-1">{{ __('Verify your email ✉️') }}</h4>
            <p class="mb-6">
                {{ __('Account activation link sent to your email address. Please follow the link inside to continue.') }}
            </p>

            <a class="btn btn-primary w-100 my-6" href="#"
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Skip for now') }}
            </a>
            <form action="{{ route('logout') }}" id="logout-form" method="POST" class="d-none">
                @csrf
            </form>
            <p class="text-center mb-0">{{ __("Didn't get the mail?") }}
                <a href="#" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                    {{ __('Resend') }} 
                </a>
            <form id="resend-form" class="d-none" method="POST" action="{{ route('verification.send') }}">
                @csrf
            </form>
            </p>
        </div>
    </div>
    <!-- / Verify email -->
@endsection

@section('script')
    <script type="text/javascript"></script>
@endsection
