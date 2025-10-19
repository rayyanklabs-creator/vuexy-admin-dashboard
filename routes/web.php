<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/', function () {
//     return redirect('/dashboard');
// });

Route::group(['middleware' => ['guest']], function () {
    // User Login Authentication Routes
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-attempt', [LoginController::class, 'login_attempt'])->name('login.attempt');
    
    // User Registration Authentication Routes
    Route::get('register', [RegisterController::class, 'register'])->name('register');
});


Route::get('/dashboard', function () {
    return view('dashboard.index'); 
})->name('dashboard');


Route::get('/reports', function () {
    return view('dashboard.report');
   
})->name('reports');