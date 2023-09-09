<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('users', [UserController::class, 'index']);
Route::get('roles', [RoleController::class, 'index']);

Route::get('permissions', [PermissionController::class, 'index']);
Route::get('permissions/all', [PermissionController::class, 'show_all']);

Route::group([ 'middleware' => 'api', 'namespace'  => 'App\Http\Controllers','prefix' => 'auth'], function ($router) {

    Route::post('register', [AuthController::class, "store"]);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);

    Route::get('user', [AuthController::class, 'user_data']);

    Route::delete('logout', [AuthController::class, 'logout']);
});
