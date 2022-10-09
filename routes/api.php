<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FruitController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get("not-login", [AuthController::class, 'no_access'])->name('login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',  [AuthController::class, 'logout']);
    Route::get('test-token', function () {
        return response("success");
    });

    // banner
});
// collector
Route::middleware('auth:sanctum')->prefix('collector')->group(function () {
    Route::get('banner-all', [BannerController::class, 'index']);
    Route::post('banner-save', [BannerController::class, 'store']);
    Route::delete('banner-delete', [BannerController::class, 'destroy']);

    // fruit
    Route::get("fruit", [FruitController::class, "index"]);
});
