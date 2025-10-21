<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\RolePermission\PermissionController;
use App\Http\Controllers\Dashboard\RolePermission\RoleController;
use App\Http\Controllers\Dashboard\User\ArchivedUserController;
use App\Http\Controllers\Dashboard\User\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::group(['middleware' => ['guest']], function () {
    // User Login Authentication Routes
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login-attempt', [LoginController::class, 'login_attempt'])->name('login.attempt');

    // User Registration Authentication Routes
    Route::get('register', [RegisterController::class, 'register'])->name('register');
    Route::post('registration-attempt', [RegisterController::class, 'register_attempt'])->name('register.attempt');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('login-verification', [AuthController::class, 'login_verification'])->name('login.verification');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    // Verified notification
    Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verification_verify'])->middleware(['signed'])->name('verification.verify');
    Route::get('email/verify', [AuthController::class, 'verification_notice'])->name('verification.notice');
    Route::post('email/verification-notification', [AuthController::class, 'verification_send'])->middleware(['throttle:2,1'])->name('verification.send');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        // User Routes
        Route::resource('user', UserController::class);
        Route::resource('archived-user', ArchivedUserController::class);
        Route::get('user/restore/{id}', [ArchivedUserController::class, 'restoreUser'])->name('archived-user.restore');
        Route::get('user/status/{id}', [UserController::class, 'updateStatus'])->name('user.status.update');
        // Role and Permission Routes
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);
    });
});
