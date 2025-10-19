<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    
    use ResetsPasswords;
    protected function redirectTo()
    {
        return '/dashboard'; 
    }
}
