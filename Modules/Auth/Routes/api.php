<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\GroupController;
use Modules\Auth\Http\Controllers\PermissionController;
use Modules\Auth\Http\Controllers\PermissionGroupController;
use Modules\Auth\Http\Controllers\PermissionUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('authJwt')->apiResource('group', GroupController::class);
Route::middleware('authJwt')->apiResource('permission', PermissionController::class);
Route::middleware('authJwt')->resource('permission.userPermission', 'PermissionUserController');
Route::middleware('authJwt')->apiResource('userPermission', PermissionUserController::class);
Route::middleware('authJwt')->resource('permission.groupPermission', 'PermissionGroupController');
Route::middleware('authJwt')->apiResource('groupPermission', PermissionGroupController::class);

Route::prefix('auth')->group(function () {


    Route::middleware('isSignIn')->post('/sign-up', [AuthController::class, 'signUp']);
    Route::middleware('isSignIn')->post('/signin-jwt', [AuthController::class, 'signIn']);
    // Protected routes

    Route::middleware('authJwt')->post('/sign-out', [AuthController::class, 'signOut']);

    Route::middleware('authJwt')->get('/is-signin', [AuthController::class, 'isSignIn']);

    Route::middleware('isSignIn')->post('/forgot', [AuthController::class, 'forgot']);

    Route::middleware('isSignIn')->post('/reset-password-email', [AuthController::class, 'resetPasswordViaEmail']);
    Route::middleware('isSignIn')->post('/reset-password-sms', [AuthController::class, 'resetPasswordViaSms']);

    Route::middleware('isSignIn')->post('/activate-account-email', [AuthController::class, 'activateAccountViaEmail']);
    Route::middleware('isSignIn')->post('/send-activate-email', [AuthController::class, 'sendActivateCodeViaEmail']);

    Route::middleware('isSignIn')->post('/activate-account-sms', [AuthController::class, 'activateAccountViaSms']);
    Route::middleware('isSignIn')->post('/send-activate-sms', [AuthController::class, 'sendActivateCodeViaSms']);


});




