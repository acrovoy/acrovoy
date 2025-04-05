<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\CoinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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