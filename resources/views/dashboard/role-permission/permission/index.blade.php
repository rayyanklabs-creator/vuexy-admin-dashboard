@extends('layouts.master')

@section('title', __('Permissions'))


@section('breadcrumb-items')
    <li class="breadcrumb-item active">{{ __('Permission') }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">

        @foreach($groupedPermissions as $moduleName => $modulePermissions)
        <!-- Dynamic Permission Section -->
        <h5 class="mt-4 mb-3 fw-bold text-primary border-bottom pb-2">
            {{ $moduleName }}
        </h5>

        <div class="table-responsive mb-5">
            <table class="table table-bordered align-middle text-center table-hover">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th >{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($modulePermissions as $permissionData)
                        @php
                            $permission = $permissionData['permission'];
                        @endphp
                        <tr>
                            <td class="text-start">
                                <span class="fw-semibold">{{ $permission->name }}</span>
                            </td>
    
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <form action="#" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure to delete this permission?')"
                                                title="Delete Permission">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach

        @if(empty($groupedPermissions))
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="bx bx-shield-x bx-lg text-muted"></i>
            </div>
            <h5 class="text-muted">No Permissions Found</h5>
            <p class="text-muted">There are no permissions configured in the system.</p>
        </div>
        @endif

    </div>
</div>
@endsection