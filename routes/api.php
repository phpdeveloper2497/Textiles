<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BoxHistoryController;
use App\Http\Controllers\HandkerchiefController;
use App\Http\Controllers\HandkerchiefHistoryController;
use App\Http\Controllers\SoldHankerchiefController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('me', 'me')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');

});

Route::get('box-histories/workshop', [BoxHistoryController::class, 'workshop']);
Route::post('handkerchiefs/{handkerchief}/view-history', [HandkerchiefController::class, 'viewHandkerchiefHistory']);
Route::post('handkerchief-histories/sold', [HandkerchiefHistoryController::class, 'sold'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index');
        Route::get('/roles', 'viewAnyRoles');
        Route::get('/{user}', 'show');
        Route::get('/{user}/update', 'update');
        Route::post('/create', 'store');
        Route::get('/{user}/delete', 'delete');
        Route::post('/{user}/assign-role', 'assignRole');
        Route::delete('/{user}/remove-role', 'removeRole');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'boxes' => BoxController::class,
        'box-histories' => BoxHistoryController::class,
        'handkerchiefs' => HandkerchiefController::class,
        'handkerchief-histories' => HandkerchiefHistoryController::class,
//    "soldhandkerchiefs" =>SoldHankerchiefController::class,
    ]);
});
