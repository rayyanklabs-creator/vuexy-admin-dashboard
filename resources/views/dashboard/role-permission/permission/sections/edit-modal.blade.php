<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-simple">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-6">
          <h3>{{ __('Edit Permission') }}</h3>
          <p class="text-body-secondary">{{ __('Edit permission as per your requirements.') }}</p>
        </div>
        <form id="editPermissionForm" class="row" method="POST">
          <div class="col-sm-9 form-control-validation">
            <label class="form-label" for="permission_name">{{ __('Permission Name') }}</label>
            <input type="text" id="permission_name" name="permission_name" class="form-control" placeholder="{{ __('Permission Name') }}" tabindex="-1" />
          </div>
          <div class="col-sm-3 mb-4">
            <label class="form-label invisible d-none d-sm-inline-block">{{ __('Button') }}</label>
            <button type="submit" class="btn btn-primary mt-1 mt-sm-0">{{ __('Update') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Permission Modal -->