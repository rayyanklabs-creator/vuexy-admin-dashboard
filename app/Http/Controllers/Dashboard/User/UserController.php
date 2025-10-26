<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use AuthorizesRequests;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->authorize('view user');

        try {
            $stats = $this->userService->getUserStats();
            return view('dashboard.users.index',  $stats);
        } catch (\Throwable $th) {
            Log::error("User Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function getUsersData(Request $request)
    {
        $this->authorize('view user');

        try {
            $users = $this->userService->getUsersForDataTablesServerSide($request);
            return response()->json($users);
        } catch (\Throwable $th) {
            Log::error("Get Users Data Failed: " . $th->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    public function store(Request $request)
    {
        $this->authorize('create user');

        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
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
                $userData = $this->userService->generateUserData($request->all());
                $user = User::create($userData);

                $user->syncRoles($request->role);

                $profileData = $this->userService->generateProfileData($user->id, $request->all());
                Profile::create($profileData);
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
            $user = $this->userService->getUsersQueryForWeb(['profile'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => $this->userService->formatUserData($user),
            ]);
        } catch (\Throwable $th) {
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
            DB::transaction(function () use ($id, $request) {
                $user = $this->userService->getUsersQueryForWeb(null)->findOrFail($id);
                $user->name = $request->edit_first_name . ' ' . $request->edit_last_name;
                $user->save();

                $user->syncRoles($request->edit_role);

                $profile = Profile::where('user_id', $user->id)->firstOrFail();
                $profile->first_name = $request->edit_first_name;
                $profile->last_name = $request->edit_last_name;
                $profile->save();
            });

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
    public function destroy($id)
    {
        $this->authorize('delete user');

        try {
            $user = $this->userService->getUsersQueryForWeb(null)->findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'Account Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error('Account Deletion Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }



    /**
     * Update status of the specified resource from storage.
     */
    public function updateStatus(string $id)
    {
        $this->authorize('update user');

        try {
            $user = $this->userService->getUsersQueryForWeb([])->findOrFail($id);
            $message = $user->is_active == 'active' ? 'Account Deactivated Successfully' : 'Account Activated Successfully';

            $user->is_active = $user->is_active == 'active' ? 'inactive' : 'active';
            $user->saveQuietly();

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::error('Account Status Updation Failed', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
