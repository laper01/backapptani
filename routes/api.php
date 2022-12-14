<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CustomerTransactionController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerTransactionController;
use App\Http\Controllers\FruitCommodityController;
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


});
// collector
Route::middleware('auth:sanctum')->prefix('collector')->group(function () {
    // banner
    Route::get('banner-all', [BannerController::class, 'index']);
    Route::post('banner-save', [BannerController::class, 'store']);
    Route::delete('banner-delete', [BannerController::class, 'destroy']);

    // fruit
    Route::get("fruit", [FruitController::class, "index"]);

    // pertani
    Route::get('farmer',[FarmerController::class, "index"]);
    Route::post('farmer',[FarmerController::class, "store"]);
    Route::put("farmer/{id}", [FarmerController::class, "update"]);
    Route::delete("farmer/{id}", [FarmerController::class, "destroy"]);

    // komoditas petaniget
    Route::get('comodity', [FruitCommodityController::class, 'index']);
    Route::get('comodity/show/{id}', [FruitCommodityController::class, 'show']);
    Route::post('comodity', [FruitCommodityController::class, 'store']);
    Route::put('comodity/{id}', [FruitCommodityController::class, 'update']);
    Route::delete('comodity/{id}', [FruitCommodityController::class, 'destroy']);
    Route::put('comodity/verify/{id}', [FruitCommodityController::class, 'valid']);
    Route::get('comodity/list/verified/', [FruitCommodityController::class, 'listVerifiedComodity']);


    Route::prefix('transaction')->group(function(){
        // transaksi petani
        Route::get('/farmer', [FarmerTransactionController::class, 'index']);
        Route::get('/farmer/show/{id}', [FarmerTransactionController::class, 'show']);
        Route::post('/farmer', [FarmerTransactionController::class, 'store']);
        //transaksi pelanggan
        Route::get('/customer', [CustomerTransactionController::class, 'index']);
        Route::get('/customer/show/{id}', [CustomerTransactionController::class, 'show']);
        Route::post('/customer', [CustomerTransactionController::class, 'store']);
    });


});
