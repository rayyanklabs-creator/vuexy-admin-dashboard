<?php

namespace App\Services;

use App\Models\User;
use App\trait\GenerateUsername;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;


class UserService extends BaseService
{
    use GenerateUsername;

    const defaultSelect = [
        'id',
        'name',
        'email',
        'username',
        'is_active',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const profileSelect = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'profile_image',
        'phone_number',
        'bio',
        'facebook_url',
        'linkedin_url',
        'instagram_url',
        'github_url'
    ];

    const loadRelation = ['profile', 'roles'];

    public function getBasicQuery()
    {
        return User::query();
    }

    public function getUsersQueryForWeb($relations = [])
    {
        $relations = is_array($relations) ? $relations : [];
        $query = $this->getBasicQuery();

        if ($relations === 'all') {
            $relations = self::loadRelation;
        }

        if (is_string($relations)) {
            $relations = [$relations];
        }

        $validRelations = array_intersect($relations, self::loadRelation);

        if (empty($validRelations)) {
            return $query;
        }

        return $query->with($validRelations);
    }

    public function getUsers($userIds = null, $relations = [])
    {

        $query = $this->getBasicQuery()->select(self::defaultSelect);
        $query = $this->getUsersQueryForWeb($relations)->select(self::defaultSelect);

        if ($userIds) {
            if (is_array($userIds) || $userIds instanceof Collection) {
                $query->whereIn('id', (array) $userIds);
            } else {
                $query->where('id', $userIds);
            }
        }

        return $query;
    }

    public function getUsersForDataTablesServerSide(Request $request)
    {
        $baseQuery = $this->getUsers();

        $searchableColumns = [
            'name',
            'email',
            'username',
        ];

        $orderableColumns = [
            'id',
            'name',
            'username',
            'email',
            'created_at',
        ];

        return $this->processDataTables(
            $request,
            $baseQuery,
            $searchableColumns,
            $orderableColumns,
            function ($user, $index) {
                return $this->formatUserForDataTables($user, $index);
            }
        );
    }

    private function formatUserForDataTables($user, $index)
    {
        return [
            'DT_RowId' => 'row_' . $user->id,
            'sr_no' => $index,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => Str::title(str_replace('-', ' ', $user->roles->first()->name ?? 'N/A')),
            'created_date' => $user->created_at->format('Y-m-d'),
            'status' => [
                'text' => ucfirst($user->is_active),
                'class' => $user->is_active == 'active' ? 'success' : 'danger'
            ],
            'actions' => view('dashboard.users.partials.actions', compact('user'))->render()
        ];
    }

    public function formatUserData(User $user)
    {
        return [
            'id' => $user->id,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name ?? null,
            'email' => $user->email ?? null,
            'role' => $user->getRoleNames()->first(),
        ];
    }

    public function generateUserData(array $data)
    {
        return [
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'username' => $this->generateUsername($data['first_name'] . ' ' . $data['last_name']),
        ];
    }

    public function generateProfileData($userId, array $data)
    {
        return [
            'user_id' => $userId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ];
    }

    public function getUserStats()
    {
        return [
            'totalUsers' => $this->getBasicQuery()->count(),
            'totalDeactivatedUsers' => $this->getBasicQuery()->where('is_active', 'inactive')->count(),
            'totalActiveUsers' => $this->getBasicQuery()->where('is_active', 'active')->count(),
            'totalArchivedUsers' => $this->getBasicQuery()->onlyTrashed()->count(),
        ];
    }
}
