@canany(['delete user', 'update user', 'view user'])
<div class="d-flex">
    @canany(['delete user'])
        @if (!($user->roles->first()->name == 'admin' || $user->roles->first()->name == 'super-admin'))
            <form action="{{ route('dashboard.user.destroy', $user->id) }}" method="POST">
                @method('DELETE')
                @csrf
                <a href="#" type="submit"
                    class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Archive User') }}">
                    <i class="ti ti-trash ti-md"></i>
                </a>
            </form>
        @endif
    @endcan
    @canany(['update user'])
        <span class="text-nowrap">
            <button
                class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1 btn-edit-user"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasEditUser"
                data-user-id="{{ $user->id }}">
                <i class="ti ti-edit ti-md"></i>
            </button>
        </span>
        <span class="text-nowrap">
            <a href="{{ route('dashboard.user.status.update', $user->id) }}"
                class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ $user->is_active == 'active' ? __('Deactivate User') : __('Activate User') }}">
                @if ($user->is_active == 'active')
                    <i class="ti ti-toggle-right ti-md text-success"></i>
                @else
                    <i class="ti ti-toggle-left ti-md text-danger"></i>
                @endif
            </a>
        </span>
    @endcan
    @can(['view user'])
        <button class="btn btn-icon btn-text-warning waves-effect waves-light rounded-pill me-1 btn-view-user"
            data-bs-toggle="modal" data-bs-target="#modalCenter"
            data-user-id="{{ $user->id }}">
            <i class="ti ti-eye ti-md"></i>
        </button>
    @endcan
</div>
@endcanany