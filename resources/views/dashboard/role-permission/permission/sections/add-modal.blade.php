<div class="modal fade" id="addPermissionModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-simple modal-dialog-centered modal-add-new-role">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-6">
                    <h4 class="role-title mb-2">{{ __('Create Permission') }}</h4>
                </div>
                <form id="addPermissionForm" class="row g-6" method="POST" action="{{route('dashboard.permission.store')}}">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="permission_name">{{ __('Permission Name') }}</label>
                        <input type="text" id="permission_name" name="permission_name" class="form-control @error('permission_name') is-invalid @enderror"
                            placeholder="{{ __('Enter permission name') }}" tabindex="-1" required/>
                        @error('permission_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
