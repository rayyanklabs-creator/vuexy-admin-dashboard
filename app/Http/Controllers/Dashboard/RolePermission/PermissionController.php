<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

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
            $permissions  = Permission::get();
            return view('dashboard.role-permission.permission.index', compact('permissions'));
        } catch (\Throwable $th) {
            // Handle the exception
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
