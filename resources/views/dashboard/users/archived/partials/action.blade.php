@canany(['delete archived user', 'restore archived user'])
    <div class="d-flex">
        @can(['delete archived user'])
            @if (!($user->getRoleNames()->first() == 'admin' || $user->getRoleNames()->first() == 'super-admin'))
                <form action="{{ route('dashboard.archived-user.destroy', $user->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href="#" type="submit"
                        class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete-record delete_confirmation"
                        data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Permanent Delete') }}">
                        <i class="ti ti-trash-x ti-md"></i>
                    </a>
                </form>
            @endif
        @endcan
        @can(['update archived user'])
            <span class="text-nowrap">
                <a href="{{ route('dashboard.archived-user.restore', $user->id) }}"
                    class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill me-1" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Restore User') }}">
                    <i class="ti ti-restore ti-md text-success"></i>
                </a>
            </span>
        @endcan
    </div>
@endcan
