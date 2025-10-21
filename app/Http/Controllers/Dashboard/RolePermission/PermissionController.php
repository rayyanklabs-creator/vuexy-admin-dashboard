<?php

namespace App\Http\Controllers\Dashboard\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Throwable;

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
            $permissions = Permission::with('roles')->get()->groupBy(function ($item) {
                return explode(' ', trim($item->name), 2)[1];
            });
            return view('dashboard.role-permission.permission.index', compact('permissions'));
        } catch (\Throwable $th) {
            Log::error("Permission Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function store(Request $request)
    {
        $this->authorize('create permission');
        try {
            $actions = ['view', 'create', 'update', 'delete'];
            $originalName = Str::slug(strtolower($request->permission_name));

            foreach ($actions as $action) {

                $permissionName = $action . ' ' . $originalName;
                $validate = Validator::make($request->all(), [
                    'permission_name' => 'required|string|max:255|unique:permissions,name',
                ]);

                if ($validate->fails()) {
                    return back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
                }

                $permission = new Permission();
                $permission->name = $permissionName;
                $permission->save();
            }

            return redirect()->back()->with('success', 'Permission created successfully');
        } catch (Throwable $th) {
            Log::error("Permission Store Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Request Failed: " . $th->getMessage());
        }
    }

    public function edit(string $id)
    {
        $this->authorize('update permission');
        try {
            $permission = Permission::findOrFail($id);
            return response()->json([
                'success' => true,
                'permission' => $permission,
            ]);
        } catch (Throwable $th) {
            Log::error("Permission Edit Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Request Failed: " . $th->getMessage());
        }
    }

    public function update() {}

    public function destroy($permissionId)
    {
        try {
            $permission = Permission::findOrFail($permissionId);
            $permission->delete();
            return redirect()->back()->with('success', 'Permission deleted successfully');
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->back()->with('error', "Request Failed:" . $th->getMessage());
        }
    }
}
