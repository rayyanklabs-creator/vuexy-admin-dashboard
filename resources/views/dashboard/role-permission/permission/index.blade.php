@extends('layouts.master')

@section('title', 'Permission')

@section('css')
    <style>
        .edit-loader {
            width: 100%;
        }

        .edit-loader .sk-chase {
            display: block;
            margin: 0 auto;
        }
    </style>
@endsection

@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Permission') }}</li>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ __('Permissions') }}</h4>
                @can('create permission')
                    <button class="btn add-new btn-primary" data-bs-target="#addPermissionModal" data-bs-toggle="modal"><span><i
                                class="menu-icon tf-icons ti ti-plus"></i><span
                                class="d-none d-sm-inline-block">{{ __('Add Permission') }}</span></span></button>
                @endcan
            </div>

            <div class="card-datatable table-responsive">
                @forelse ($permissions as $entity => $entityPermissions)
                    <div class="mt-4 mb-3 px-3">
                        <h5 class="fw-bold text-primary border-bottom pb-2 mb-0">
                            {{ ucfirst($entity) }}
                        </h5>
                    </div>

                    <table class="datatables-permissions table border-top custom-datatables">
                        <thead>
                            <tr>
                                <th>{{ __('Sr.') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Assigned To Role') }}</th>
                                <th>{{ __('Created At') }}</th>
                                @canany(['update permission', 'delete permission'])
                                    <th width="15%">{{ __('Action') }}</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($entityPermissions as $index => $permission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start">
                                        {{ ucfirst(Str::before($permission->name, ' ')) }}
                                    </td>

                                    <td>
                                        @if ($permission->roles->count() > 0)
                                            @foreach ($permission->roles as $role)
                                                <span class="badge me-1 bg-label-primary">
                                                    {{ Str::title(str_replace('-', ' ', $role->name)) }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-secondary">{{ __('No Roles Assigned') }}</span>
                                        @endif
                                    </td>


                                    <td>{{ $permission->created_at->format('Y-m-d') }}</td>

                                    @canany(['update permission', 'delete permission'])
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('update permission')
                                                    <button
                                                        class="btn btn-icon btn-text-primary waves-effect waves-light rounded-pill"
                                                        data-bs-target="#editPermissionModal" data-bs-toggle="modal"
                                                        data-permission-id="{{ $permission->id }}"
                                                        title="{{ __('Edit Permission') }}">
                                                        <i class="ti ti-pencil ti-md"></i>
                                                    </button>
                                                @endcan

                                                @can('delete permission')
                                                    <form action="{{ route('dashboard.permissions.destroy', $permission->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#"
                                                            class="btn btn-icon btn-text-danger waves-effect waves-light rounded-pill delete_confirmation"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete Permission') }}">
                                                            <i class="ti ti-trash ti-md"></i>
                                                        </a>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bx bx-shield-x bx-lg text-muted"></i>
                        </div>
                        <h5 class="text-muted">{{ __('No Permissions Found') }}</h5>
                        <p class="text-muted">{{ __('There are no permissions configured in the system.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
        <!-- Add Permission Modal -->
        @include('dashboard.role-permission.permission.sections.add-modal')
        <!--/ Add Permission Modal -->

        <!-- Edit Role Modal -->
        @include('dashboard.role-permission.permission.sections.edit-modal')
        <!-- / Edit Role Modal -->
    @endsection

    @section('script')
        <script src="{{ asset('assets/js/modal-add-permission.js') }}"></script>
        <script src="{{ asset('assets/js/modal-edit-permission.js') }}"></script>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.dataTables_paginate').hide();
                }, 300);
            });
        </script>

        <script>
            $(document).ready(function() {
                $('.edit-loader').hide();
                $('#edit-permission-modal').hide();

                $('#editPermissionModal').on('show.bs.modal', function(event) {
                    $('.edit-loader').show();
                    $('#edit-permission-modal').hide();
                    var button = $(event.relatedTarget);
                    var permissionId = button.data('permission-id');
                    fetchPermissionData(permissionId);
                });

                var editPermissionRoute = "{{ route('dashboard.permissions.edit', ':permissionId') }}";
                var updatePermissionRoute = "{{ route('dashboard.permissions.update', ':permissionId') }}";

                function fetchPermissionData(permissionId) {
                    var url = editPermissionRoute.replace(':permissionId', permissionId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            var permission = data.permission;
                            console.log("AJAX RUN SUCESSFULLY", permission);
                        }
                    });
                }
            });
        </script>
    @endsection


    @section('data-table-script')
        <script>
            initClientSideDataTable();
        </script>
    @endsection
