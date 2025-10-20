<?php

namespace App\Http\Controllers\Dashboard\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArchivedUserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view archived user');
        try {
            $archivedUsers = User::onlyTrashed()->get();
            return view('dashboard.users.archived.index', compact('archivedUsers'));
        } catch (\Throwable $th) {
            Log::error("Archived User Index Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete archived user');
        try {
            $user = User::withTrashed()->findOrFail($id);

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
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return redirect()->route('dashboard.archived-user.index')->with('success', 'User Restored Successfully');
        } catch (\Throwable $th) {
            // Handle the exception
            // throw $th;
            Log::error("Archived User restore Failed:" . $th->getMessage());
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }
}
