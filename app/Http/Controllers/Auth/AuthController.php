<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    /**
     * User Logout
     */
    public function logout()
    {
        try {
            Auth::logout();
            return Redirect::route('login')->with('success', 'Logout Successfully!');
        } catch (\Throwable $th) {
            return Redirect::back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function login_verification()
    {
        if (Auth::user() && Auth::user()->email_verified_at !== null) {
            return view('auth.verification');
        } else {
            return redirect()->route('dashboard');
        }
    }

    public function verification_verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('dashboard');
    }

    public function verification_notice()
    {
        try {
            $user = Auth::user();
            if ($user->email_verified_at !== null) {
                return redirect()->route('dashboard');
            }
            return view('auth.verify-email');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', "Something went wrong! Please try again later");
        }
    }

    public function verification_send(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}
