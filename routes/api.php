<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BoxHistoryController;
use App\Http\Controllers\HandkerchiefController;
use App\Http\Controllers\HandkerchiefHistoryController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login',[AuthController::class,'login']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::get('me', 'me')->middleware('auth:sanctum');
    Route::post('logout', 'logout')->middleware('auth:sanctum');

});

//Route::get('boxes/{id}/workshop',[BoxController::class,'workshop']);
Route::get('box-history/workshop',[BoxHistoryController::class,'workshop']);
Route::post('handkerchief-history/sold',[HandkerchiefHistoryController::class,'sold']);

Route::apiResources([
    'users' => UserController::class,
    'boxes' => BoxController::class,
    'box-history' => BoxHistoryController::class,
    'handkerchiefs' => HandkerchiefController::class,
    'handkerchief-history' => HandkerchiefHistoryController::class
]);

