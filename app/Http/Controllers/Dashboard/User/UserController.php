<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\trait\GenerateUsername;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests, GenerateUsername;

    public function index()
    {

        $this->authorize('view user');
        try {
            $users = User::with('profile')->get();
            $totalUsers = User::count();
            $totalDeactivatedUsers = User::where('is_active', 'inactive')->count();
            $totalActiveUsers = User::where('is_active', 'active')->count();
            $totalUnverifiedUsers = User::where('email_verified_at', null)->count();
            $totalArchivedUsers = User::onlyTrashed()->count();
            $roles = Role::all();
            return view('dashboard.users.index', compact('users', 'totalUsers', 'totalDeactivatedUsers', 'totalActiveUsers', 'totalUnverifiedUsers', 'roles', 'totalArchivedUsers'));
        } catch (\Throwable $th) {
            Log::error("User Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create user');
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirm-password' => 'required|same:password',
            'role' => 'required|exists:roles,name'
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::transaction(function () use ($request) {

                $user = new User();
                $user->name = $request->first_name . ' ' . $request->last_name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->email_verified_at = now();
                $user->username = $this->generateUsername($request->first_name . ' ' . $request->last_name);
                $user->save();

                $user->syncRoles($request->role);

                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->first_name = $request->first_name;
                $profile->last_name = $request->last_name;
                $profile->save();
            });
            return redirect()->route('dashboard.user.index')->with('success', 'User created successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("User Store Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Gate::any(['update user', 'view user'])) {
            abort(403, 'Unauthorized');
        }
        try {
            $user = User::with('profile')->findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => [
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
                ],
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            Log::error("User Edit Failed:" . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => "Something went wrong! Please try again later"
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('update user');
        $validate = Validator::make($request->all(), [
            'edit_first_name' => 'required|string|max:255',
            'edit_last_name' => 'required|string|max:255',
            'edit_role' => 'required|exists:roles,name'
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->name = $request->edit_first_name . ' ' . $request->edit_last_name;
            $user->save();

            $user->syncRoles($request->edit_role);

            $profile = Profile::where('user_id', $user->id)->firstOrFail();
            $profile->first_name = $request->edit_first_name;
            $profile->last_name = $request->edit_last_name;
            $profile->save();

            DB::commit();

            return redirect()->route('dashboard.user.index')->with('success', 'User updated successfully');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("User Update Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete user');
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->back()->with('success', 'Account Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Account Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }



    /**
     * Update status of the specified resource from storage.
     */
    public function updateStatus(string $id)
    {
        $this->authorize('update user');
        try {
            $user = User::findOrFail($id);
            $message = $user->is_active == 'active' ? 'Account Deactivated Successfully' : 'Account Activated Successfully';
            if ($user->is_active == 'active') {
                $user->is_active = 'inactive';
                $user->saveQuietly();
            } else {
                $user->is_active = 'active';
                $user->saveQuietly();
            }
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Account Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
            throw $th;
        }
    }
}
