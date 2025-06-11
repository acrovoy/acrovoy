<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\OctoSettingController;
use App\Http\Controllers\Api\CoinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\GmtController;
use App\Http\Controllers\Api\PromocodeController;
use App\Http\Controllers\Api\OctoEventController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\Api\KeyController;
use App\Http\Controllers\Api\AdminnController;
use App\Http\Controllers\Api\ChangeSyncController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

Route::get('/fcoins', [CoinController::class, 'getAllFuturesCoins']);
Route::get('/coins', [CoinController::class, 'getAllSpotCoins']);

});


Route::post('/invoice/store', [InvoiceController::class, 'store']);
Route::middleware('auth:sanctum')->post('/check-invoice-status', [InvoiceController::class, 'checkInvoiceStatus']);
Route::middleware('auth:sanctum')->post('/invoice/get-invoice', [InvoiceController::class, 'getInvoiceByEmail']);
// Route::middleware('auth:sanctum')->get('/keys', [KeyController::class, 'index']);
Route::middleware('auth:sanctum')->get('/keys', [KeyController::class, 'index']);
Route::middleware('auth:sanctum')->post('/keys/name', [KeyController::class, 'getKeysByName']);


Route::post('/invoice/get-product-invoice', [InvoiceController::class, 'getInvoiceByEmailAndProduct']);
Route::post('/invoice/paid', [InvoiceController::class, 'markAsPaid']);
Route::get('/settings/by-email', [OctoSettingController::class, 'getByEmail']);
Route::get('/settings/{email}', [SettingController::class, 'getSettingsByEmail']);
Route::post('/settings/create/{user_id}', [SettingController::class, 'createDefaultSettings']);
Route::post('/settings/update', [SettingController::class, 'updateSettingsByEmail']);
Route::post('/settings/market/update', [SettingController::class, 'updateMarketByEmail']);
Route::post('/inv/chpm', [InvoiceController::class, 'checkPm']);
Route::get('/product/{app}', [ProductController::class, 'getCurrentVersion']);
Route::get('/active-ad', [AdController::class, 'getActiveAd']);
Route::get('/notice/{email}', [NoticeController::class, 'show']);
Route::post('/notice', [NoticeController::class, 'store']);
Route::get('/promocode/price', [PromocodeController::class, 'getPrice']);
Route::get('/promocode/ownprice', [PromocodeController::class, 'getOwnPrice']);
Route::post('/callback', [PaymentCallbackController::class, 'handle']);
Route::get('/gmts', [GmtController::class, 'index']);
Route::post('/octo-settings/update-gmt', [OctoSettingController::class, 'updateGmt']);
Route::post('/octo-settings/update-theme', [OctoSettingController::class, 'updateTheme']);
Route::post('/octo-settings/update-signal', [OctoSettingController::class, 'updateSignal']);
Route::get('/octo-events', [OctoEventController::class, 'getEvents']);
Route::post('/octo-events/store-user', [OctoEventController::class, 'storeUserEvent']);


Route::post('/user_data/check-user-staus', [InvoiceController::class, 'checkUserStatus']);
Route::get('/settings/gmt/email', [OctoSettingController::class, 'getGmtByEmail']);
Route::get('/keys', [KeyController::class, 'index']);


#Adminn api

Route::post('/adevent/add-event', [AdminnController::class, 'addEvent']);
Route::post('/adevent/delete-all-events', [AdminnController::class, 'deleteEvents']);
Route::post('/adevent/delete-part-event', [AdminnController::class, 'deletePartEvent']);
Route::get('/adevent/users', [AdminnController::class, 'getOctopoyUsers']);
Route::post('/adevent/add-personel-msgevent', [AdminnController::class, 'addPersonelEvent']);

Route::get('/adevent/get-events', [AdminnController::class, 'getEvents']);
Route::post('/adevent/delete-one-event', [AdminnController::class, 'deleteOneEvent']);
Route::post('/adevent/total-params', [AdminnController::class, 'getTotalParams']);
Route::post('/adevent/get-sale-list', [AdminnController::class, 'getSalesList']);
Route::get('/adevent/get-users-online', [AdminnController::class, 'getUsersOnline']);
Route::get('/adevent/get-all-users', [AdminnController::class, 'getUsers']);
Route::post('/adevent/get-all-downloads', [AdminnController::class, 'getAllDownloads']);
Route::post('/changes', [ChangeSyncController::class, 'getChanges']);
Route::post('/constants/update', [ChangeSyncController::class, 'updateConstant']);
Route::get('/constants/get', [ChangeSyncController::class, 'getConstant']);

Route::post('/proxy/update', [SettingController::class, 'updateProxyByEmail']);

