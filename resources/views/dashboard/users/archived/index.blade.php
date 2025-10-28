@extends('layouts.master')

@section('title', __('Archived Users'))

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
    <li class="breadcrumb-item active">{{ __('Archived Users') }}</li>
@endsection
{{-- @dd($totalArchivedUsers) --}}
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-users table border-top custom-datatables">
                    <thead>
                        <tr>
                            <th>{{ __('Sr.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Deletion Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            @canany(['delete archived user', 'restore archived user'])<th>{{ __('Action') }}</th>@endcan
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script></script>
@endsection
@section('data-table-script')
    <script>
        let archivedColumns = [{
                data: 'sr_no',
                name: 'sr_no',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'role',
                name: 'role',
                orderable: false
            },
            {
                data: 'deleted_at',
                name: 'deleted_at'
            },
            {
                data: 'status',
                name: 'status',
                render: function(data) {
                    return '<span class="badge me-4 bg-label-' + data.class + '">' + data.text + '</span>';
                },
                orderable: false
            },
            @canany(['delete archived user', 'update archived user', 'view archived user'])
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                }
            @endcanany
        ];


        let archivedUserDataTable = initServerSideDataTable("{{ route('dashboard.archived-user.data') }}",
        archivedColumns);

        $(document).on('ajaxComplete', function(event, xhr, settings) {
            if (settings.url.includes('archived-user.update') || settings.url.includes('archived-user.destroy')) {
                if (userDataTable) {
                    userDataTable.ajax.reload(null, false);
                }
            }
        });
    </script>
@endsection
