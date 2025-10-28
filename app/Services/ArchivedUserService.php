<?php

namespace App\Services;

use App\Models\User;
use App\trait\GenerateUsername;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class ArchivedUserService extends BaseService
{
    use GenerateUsername;

    const defaultSelect = [
        'id',
        'name',
        'email',
        'deleted_at'
    ];

    public function getBasicQuery()
    {
        return User::query();
    }

    public function getUsersWithStatsQuery()
    {
        return $this->getBasicQuery();
    }

    public function getArchivedUsersQuery()
    {
        return $this->getBasicQuery()->onlyTrashed();
    }

    public function getArchivedUserById($id)
    {
        return $this->getBasicQuery()->withTrashed()->findOrFail($id);
    }

    public function getArchivedUsersForDataTablesServerSide(Request $request, array $searchableColumns, array $orderableColumns)
    {
        $baseQuery = $this->getBasicQuery()->onlyTrashed();

        return $this->processDataTables(
            $request,
            $baseQuery,
            $searchableColumns,
            $orderableColumns,
            function ($user, $index) {
                return $this->formatArchivedUserForDataTables($user, $index);
            }
        );
    }

    private function formatArchivedUserForDataTables($user, $index)
    {
        return [
            'DT_RowId' => 'row_' . $user->id,
            'sr_no' => $index,
            'name' => $user->name,
            'email' => $user->email,
            'role' => Str::title(str_replace('-', ' ', $user->roles->first()->name ?? 'N/A')),
            'deleted_at' => $user->deleted_at ? $user->deleted_at->format('Y-m-d') : null,
            'status' => [
                'text' => ucfirst($user->is_active),
                'class' => $user->is_active == 'active' ? 'success' : 'danger'
            ],
            'actions' => view('dashboard.users.archived.partials.action', compact('user'))->render()
        ];
    }

}
