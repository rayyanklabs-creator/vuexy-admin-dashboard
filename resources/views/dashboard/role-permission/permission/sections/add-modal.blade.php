<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-simple">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h3>{{ __('Create Permission') }}</h3>
                    <p class="text-body-secondary">{{ __('Permissions you may use and assign to your users.') }}</p>
                </div>
                <form id="addPermissionForm" class="row" method="POST" action="{{route('dashboard.permissions.store')}}">
                    @csrf
                    <div class="col-12 form-control-validation mb-4">
                        <label class="form-label" for="permission_name">{{ __('Permission Name') }}</label>
                        <input type="text" id="permission_name" name="permission_name" class="form-control @error('permission_name') is-invalid @enderror"
                            placeholder="{{ __('Enter permission name') }}" autofocus />
                        @error('permission_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>          
                    <div class="col-12 text-center demo-vertical-spacing">
                        @canany(['create permission'])<button type="submit" class="btn btn-primary me-sm-4 me-1">{{ __('Submit') }}</button>@endcan
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                            aria-label="Close">{{ __('Discard') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Add Permission Modal -->
