<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Modules\Common\Http\Controllers\ProfileController;
use Modules\Common\Http\Controllers\SettingController;
use Modules\Common\Http\Controllers\UserController;

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
Route::middleware('authJwt')->apiResource('setting', SettingController::class);
Route::middleware('authJwt')->apiResource('profile', ProfileController::class);
Route::middleware('authJwt')->apiResource('user', UserController::class);
