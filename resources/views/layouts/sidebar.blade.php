<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{asset('assets/img/logo/default.svg')}}" alt="{{env('APP_NAME')}}">
            </span>
            {{-- <span class="app-brand-text demo menu-text fw-bold">{{\App\Helpers\Helper::getCompanyName()}}</span> --}}
            <span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{__('Dashboard')}}</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small">
            <span class="menu-header-text">{{__('Apps & Pages')}}</span>
        </li>
        <li class="menu-item {{ request()->routeIs('reports') ? 'active' : '' }}">
            <a href="{{ route('reports') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-address-book"></i> {{-- Contacts --}}
                <div>{{ __('Reports') }}</div>
            </a>
        </li>
        {{-- @can(['view contact'])
        @endcan    --}}
    </ul>
</aside>
