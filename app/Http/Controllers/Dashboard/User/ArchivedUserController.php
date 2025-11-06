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
            $searchableColumns = [
                'name',
                'email',
            ];

            $orderableColumns = [
                'id',
                'name',
                'email',
                'deleted_at',
            ];
            $archivedUsers = $this->archivedUserService->getArchivedUsersForDataTablesServerSide($request, $searchableColumns, $orderableColumns);
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


    public function bulkDelete(Request $request)
    {

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['error' => true, 'message' => 'No users selected.'], 500);
        }
        try {
            
            $this->archivedUserService->getArchivedUsersByIds($ids)->forceDelete();
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' user(s) deleted successfully.'
            ]);
        } catch (\Throwable $th) {

            Log::error('Bulk User Delete Failed', ['error' => $th->getMessage()]);
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong! Please try again later'
            ], 500);
        }
    }
}
