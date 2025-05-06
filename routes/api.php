<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\CoinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Api\PromocodeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:sanctum');
Route::get('/fcoins', [CoinController::class, 'getAllFuturesCoins']);
Route::get('/coins', [CoinController::class, 'getAllSpotCoins']);
Route::post('/invoice/store', [InvoiceController::class, 'store']);
Route::middleware('auth:sanctum')->post('/check-invoice-status', [InvoiceController::class, 'checkInvoiceStatus']);
Route::middleware('auth:sanctum')->post('/invoice/get-invoice', [InvoiceController::class, 'getInvoiceByEmail']);
Route::post('/invoice/paid', [InvoiceController::class, 'markAsPaid']);
Route::get('/settings/{email}', [SettingController::class, 'getSettingsByEmail']);
Route::post('/settings/create/{user_id}', [SettingController::class, 'createDefaultSettings']);
Route::post('/settings/update', [SettingController::class, 'updateSettingsByEmail']);
Route::post('/settings/market/update', [SettingController::class, 'updateMarketByEmail']);
Route::post('/inv/chpm', [InvoiceController::class, 'checkPm']);
Route::get('/version/{app}', [ProductController::class, 'getCurrentVersion']);
Route::get('/active-ad', [AdController::class, 'getActiveAd']);
Route::get('/notice/{email}', [NoticeController::class, 'show']);
Route::post('/notice', [NoticeController::class, 'store']);
Route::get('/promocode/price', [PromocodeController::class, 'getPrice']);
