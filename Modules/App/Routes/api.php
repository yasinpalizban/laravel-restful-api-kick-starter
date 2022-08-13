<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \Modules\App\Http\Controllers\OverViewController;
use \Modules\App\Http\Controllers\GraphController;
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

Route::middleware('authJwt')->apiResource('overView',OverViewController::class );
Route::middleware('authJwt')->apiResource('graph', GraphController::class);

