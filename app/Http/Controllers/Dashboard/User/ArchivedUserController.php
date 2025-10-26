<?php

namespace App\Http\Controllers\Dashboard\User;
use App\Http\Controllers\Controller;
use App\Services\ArchivedUserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArchivedUserController extends Controller
{
    use AuthorizesRequests;

    protected $archivedUserService;

    public function __construct(ArchivedUserService $archivedUserService)
    {
        $this->archivedUserService = $archivedUserService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        try {
            return view('dashboard.users.archived.index');
        } catch (\Throwable $th) {
            Log::error("Archived User Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }


    public function getArchivedUsersData(Request $request)
    {
        
        $this->authorize('view archived user');

        try {
            $archivedUsers = $this->archivedUserService->getArchivedUsersForDataTablesServerSide($request);
            return response()->json($archivedUsers);
        } catch (\Throwable $th) {
            Log::error("Get Archived Users Data Failed: " . $th->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete archived user');

        try {
            $user = $this->archivedUserService->getArchivedUserById($id);
            $user->forceDelete();

            return redirect()->route('dashboard.archived-user.index')->with('success', 'User Permanently Deleted Successfully');
        } catch (\Throwable $th) {
            Log::error("Archived User destroy Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function restoreUser($id)
    {
        $this->authorize('update archived user');

        try {
            $user = $this->archivedUserService->getArchivedUserById($id);
            $user->restore();

            return redirect()->route('dashboard.archived-user.index')->with('success', 'User Restored Successfully');
        } catch (\Throwable $th) {
            Log::error("Archived User restore Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
