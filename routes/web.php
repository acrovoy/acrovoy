<?php
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', function () {
    return view('main');
})->name('home');

// Защищенный маршрут для панели
Route::middleware([
    'auth',       // Проверка аутентификации через сессии
    // 'verified',   // Проверка подтверждения email (если требуется)
])->group(function () {

    Route::get('/dashboard', [SaleController::class, 'dashboard'])->name('dashboard');
    Route::get('/add_product', [SaleController::class, 'addProducts'])->name('add_product');
    Route::get('/sales/{product_id}', [SaleController::class, 'salesPage'])->name('salespage');
    Route::post('/sales/update-products', [App\Http\Controllers\SaleController::class, 'updateProductList'])->name('sales.updateProducts');

    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('password.update.custom');
    Route::post('/sale/update-price', [SaleController::class, 'updatePrice'])->name('sale.updatePrice');


});

