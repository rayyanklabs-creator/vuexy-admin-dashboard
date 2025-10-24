<?php

namespace App\Services;

use App\Models\User;
use App\trait\GenerateUsername;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
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
        'updated_at'
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

    public function getBasicQuery()
    {
        return User::query();
    }

    public function getUsersWithStatsQuery()
    {
        return $this->getBasicQuery();
    }

    public function getUsersWithProfile($select = null, $profileSelect = null)
    {
        return $this->getBasicQuery()
            ->select($select ?? self::defaultSelect)
            ->with(['profile' => function($query) use ($profileSelect) {
                $query->select($profileSelect ?? self::profileSelect);
            }]);
    }

    public function getUserById($id, $withProfile = true)
    {
        $query = $this->getBasicQuery();
        
        if ($withProfile) {
            $query->with('profile');
        }
        
        return $query->findOrFail($id);
    }

    public function getArchivedUsersQuery()
    {
        return $this->getBasicQuery()->onlyTrashed();
    }

    public function getArchivedUserById($id)
    {
        return $this->getBasicQuery()->withTrashed()->findOrFail($id);
    }

    public function getUserStats()
    {
        return [
            'totalUsers' => $this->getBasicQuery()->count(),
            'totalDeactivatedUsers' => $this->getBasicQuery()->where('is_active', 'inactive')->count(),
            'totalActiveUsers' => $this->getBasicQuery()->where('is_active', 'active')->count(),
            'totalUnverifiedUsers' => $this->getBasicQuery()->where('email_verified_at', null)->count(),
            'totalArchivedUsers' => $this->getBasicQuery()->onlyTrashed()->count(),
        ];
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function searchUsers($query, $keyword)
    {
        $keyword = trim($keyword);
        if (!$keyword) {
            return $query;
        }

        return $query->where(function($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")
              ->orWhere('email', 'LIKE', "%{$keyword}%")
              ->orWhere('username', 'LIKE', "%{$keyword}%")
              ->orWhereHas('profile', function($profileQuery) use ($keyword) {
                  $profileQuery->where('first_name', 'LIKE', "%{$keyword}%")
                             ->orWhere('last_name', 'LIKE', "%{$keyword}%")
                             ->orWhere('phone_number', 'LIKE', "%{$keyword}%");
              });
        });
    }

    public function filterByStatus($query, $status)
    {
        if ($status === 'active') {
            return $query->where('is_active', 'active');
        } elseif ($status === 'inactive') {
            return $query->where('is_active', 'inactive');
        } elseif ($status === 'unverified') {
            return $query->whereNull('email_verified_at');
        }
        
        return $query;
    }

    public function filterByRole($query, $role)
    {
        return $query->role($role);
    }

    public function formatUserData(User $user)
    {
        return [
            'id' => $user->id,
            'first_name' => $user->profile->first_name,
            'last_name' => $user->profile->last_name ?? null,
            'email' => $user->email ?? null,
            'username' => $user->username ?? null,
            'role' => $user->getRoleNames()->first(),
            'full_name' => $user->name,
            'is_active' => $user->is_active,
            'profile_image' => $user->profile->profile_image ?? null,
            'dob' => $user->profile->dob ?? null,
            'phone_number' => $user->profile->phone_number,
            'bio' => $user->profile->bio ?? null,
            'facebook_url' => $user->profile->facebook_url ?? null,
            'linkedin_url' => $user->profile->linkedin_url ?? null,
            'instagram_url' => $user->profile->instagram_url ?? null,
            'github_url' => $user->profile->github_url ?? null,
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
}