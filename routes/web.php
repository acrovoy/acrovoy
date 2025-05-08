<?php
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Models\Products;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Download;

// Главная страница
Route::get('/', function () {
    $product = Products::where('id', 1)->latest()->first(); 
    return view('main', compact('product'));
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


Route::get('/download/orderscanner', function () {
    $file = storage_path('app/public/orderscanner/OrderScannerSetup.exe');

    // Проверка существования файла
    if (!file_exists($file)) {
        abort(404, 'Файл не найден');
    }

    // Логируем скачивание
    $product_id = 1;  // Здесь укажите актуальный id продукта

    // Создаем запись о скачивании с IP-адресом пользователя
    Download::create([
        'product_id' => $product_id,
        'ip_address' => request()->ip(),
    ]);

    // Возвращаем файл на скачивание
    return Response::download($file);
})->name('download.orderscanner');

