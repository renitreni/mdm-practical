<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExportVoucherController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\Invokeables\GetGroupAdmin;
use App\Http\Controllers\API\Invokeables\GetUser;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VoucherController;
use App\Http\Controllers\MyGroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('api')->group(function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class)->only(['index']);
    Route::apiResource('groups', GroupController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('vouchers', VoucherController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('my-group', MyGroupController::class)->only(['index']);

    Route::post('assign/member', [GroupController::class, 'assignMember']);
    Route::delete('remove/member/{groupMember}', [GroupController::class, 'removeMember']);

    Route::get('group-admins', GetGroupAdmin::class)->middleware('can:view-groups');
    Route::get('user-list', GetUser::class)->middleware('can:view-users');

    Route::post('export/user', [ExportVoucherController::class, 'exportByUser'])->middleware('can:export-vouchers');
    Route::get('export/all/users', [ExportVoucherController::class, 'exportAll'])->middleware('can:export-all-vouchers');
});
