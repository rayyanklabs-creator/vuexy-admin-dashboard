<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{asset('assets')}}/" data-template="vertical-menu-template" data-style="light">

<head>
    {{-- <title>{{ \App\Helpers\Helper::getCompanyName() }} - @yield('title')</title> --}}
    <title> - @yield('title')</title>
    @include('layouts.meta')
    @include('layouts.css')
    @yield('css')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Basic Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                                </li>
                                @yield('breadcrumb-items')
                            </ol>
                        </nav>

                        <!-- Basic Breadcrumb -->
                         <div id="page-content">
                             @yield('content')
                         </div>
                    </div>
                    <!-- Content -->
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- JS -->
    @include('layouts.script')
</body>

</html>
