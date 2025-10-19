<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view role');
        try {
            // Fetch the top two roles
            $adminRoles = Role::whereIn('name', ['super-admin', 'admin'])->get();

            // Get the remaining roles
            $allRoles = Role::get();

            $permissions = Permission::get();
            return view('dashboard.role-permission.role.index', compact('adminRoles', 'allRoles', 'permissions'));
        } catch (\Throwable $th) {
            //throw $th;
            Log::error("Roles Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
