<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $permissions = Permission::with('roles')->get()->groupBy(function($item){
            return explode(' ', trim($item->name),2)[1];
        });

        // dd($permissions);
        return view('dashboard.role-permission.permission.index', compact('permissions'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function store(Request $request) {}
}
