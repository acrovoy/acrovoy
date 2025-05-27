<?php
use App\Http\Controllers\SaleController;
use App\Mail\ContactFormMail;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Models\Products;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Download;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


// Главная страница
Route::get('/', function () {
    $product = Products::where('id', 1)->latest()->first(); 
    $data = Download::where('product_id', 1)->count();
    $downloaded = $data + 948;
    
    return view('main', compact('product', 'downloaded'));
})->name('home');

Route::get('/successful-payment', function () {
    return view('successful-payment');
})->name('successful-payment');

Route::get('/failed-payment', function () {
    return view('failed-payment');
})->name('failed-payment');







// Защищенный маршрут для панели
Route::middleware([
    'auth',       // Проверка аутентификации через сессии
    // 'verified',   // Проверка подтверждения email (если требуется)
])->group(function () {

    Route::get('/dashboard', [SaleController::class, 'dashboard'])->name('dashboard');
    Route::get('/add_product', [SaleController::class, 'addProducts'])->name('add_product');
    Route::get('/sales/{product_id}', [SaleController::class, 'salesPage'])->name('salespage');
    Route::post('/sales/update-products', [SaleController::class, 'updateProductList'])->name('sales.updateProducts');

    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('password.update.custom');
    Route::post('/sale/update-price', [SaleController::class, 'updatePrice'])->name('sale.updatePrice');


});


Route::get('/download/orderscanner', function () {
    $file = storage_path('app/public/orderscanner/OrderScanner101Setup.exe');

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


Route::get('/download/orderscanner208', function () {
    $file = storage_path('app/public/orderscanner/OrderScanner208Setup.exe');

    // Проверка существования файла
    if (!file_exists($file)) {
        abort(404, 'Файл не найден');
    }

    // Логируем скачивание
    $product_id = 2;  // Здесь укажите актуальный id продукта

    // Создаем запись о скачивании с IP-адресом пользователя
    Download::create([
        'product_id' => $product_id,
        'ip_address' => request()->ip(),
    ]);

    // Возвращаем файл на скачивание
    return Response::download($file);
})->name('download.orderscanner208');




Route::get('/lang/{locale}', function (string $locale) {
    $supportedLocales = ['en', 'es', 'fr', 'ru', 'de', 'cn'];

    if (!in_array($locale, $supportedLocales)) {
        abort(400, 'Unsupported locale');
    }

    Session::put('locale', $locale);
    App::setLocale($locale);
    return redirect()->back();
})->name('lang.switch');


Route::get('/orderscanner101', function () {
    $product = Products::where('id', 1)->latest()->first(); 
    $data = Download::where('product_id', 1)->count();
    $downloaded = $data + 948;
    
    return view('orderscanner101', compact('product', 'downloaded'));
})->name('orderscanner101');


Route::get('/orderscanner208', function () {
    $product = Products::where('id', 3)->latest()->first(); 
    $data = Download::where('product_id', 2)->count();
    $downloaded = $data;
    
    return view('orderscanner208', compact('product', 'downloaded'));
})->name('orderscanner208');

Route::get('/development', function () {
    return view('development');
})->name('development');

Route::get('/service', function () {
    return view('service');
})->name('service');

Route::get('/collaboration', function () {
    return view('collaboration');
})->name('collaboration');

Route::get('/vision', function () {
    return view('vision');
})->name('vision');

Route::get('/standards', function () {
    return view('standards');
})->name('standards');

Route::get('/marketing', function () {
    return view('marketing');
})->name('marketing');

Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
        'g-recaptcha-response' => 'required|captcha',
    ]);

  Mail::to('support@acrovoy.com')->send(new ContactFormMail($validated));

    return redirect()->route('contact')->with('success', __('contact.success'));
})->name('contact.send');

Route::get('/search', [SearchController::class, 'index'])->name('search');

