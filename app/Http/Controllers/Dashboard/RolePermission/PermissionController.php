<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
        $this->authorize('view permission');
        $permissions = Permission::with('roles')->get();

        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $moduleName = $this->extractModuleName($permission->name);
            $groupedPermissions[$moduleName][] = ['permission' => $permission];
        }

        return view('dashboard.role-permission.permission.index', compact('permissions', 'groupedPermissions'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    private function extractModuleName($permissionName)
    {
        $parts = explode(' ', $permissionName);
        if (count($parts) > 1) {
            return ucfirst($parts[1]); 
        }
        return ucfirst($permissionName); 
    }
}
