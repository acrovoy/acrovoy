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
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ConstantController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ManufacturedProductController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\RawMaterialController;


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
    $product_id = 3;  // Здесь укажите актуальный id продукта

    // Создаем запись о скачивании с IP-адресом пользователя
    Download::create([
        'product_id' => $product_id,
        'ip_address' => request()->ip(),
    ]);

    // Возвращаем файл на скачивание
    return Response::download($file);
})->name('download.orderscanner208');


Route::get('/download/octopoy528', function () {
    $file = storage_path('app/public/octopoy/Octopoy528Setup.exe');

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
})->name('download.octopoy528');




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
    $data = Download::where('product_id', 3)->count();
    $downloaded = $data;
    
    return view('orderscanner208', compact('product', 'downloaded'));
})->name('orderscanner208');


Route::get('/octopoy528', function () {
    $product = Products::where('id', 2)->latest()->first(); 
    $data = Download::where('product_id', 2)->count();
    $downloaded = $data + 1091;
    
    return view('octopoy528', compact('product', 'downloaded'));
})->name('octopoy528');



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


// Acounting

Route::get('/markell_rattan', [\App\Http\Controllers\AccountingController::class, 'index'])->name('accounting.index');
Route::prefix('accounting')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('accounting.index');

    // Доходы
    Route::get('/income/create', [AccountingController::class, 'createIncome'])->name('income.create');
    Route::post('/income/store', [AccountingController::class, 'storeIncome'])->name('income.store');

    // Расходы
    Route::get('/expense/create', [AccountingController::class, 'createExpense'])->name('expense.create');
    Route::post('/expense/store', [AccountingController::class, 'storeExpense'])->name('expense.store');
});

// Доходы
Route::get('/income/{income}/edit', [AccountingController::class, 'editIncome'])->name('income.edit');
Route::put('/income/{income}', [AccountingController::class, 'updateIncome'])->name('income.update');
Route::delete('/income/{income}', [AccountingController::class, 'destroyIncome'])->name('income.destroy');

// Расходы
Route::get('/expense/{expense}/edit', [AccountingController::class, 'editExpense'])->name('expense.edit');
Route::put('/expense/{expense}', [AccountingController::class, 'updateExpense'])->name('expense.update');
Route::delete('/expense/{expense}', [AccountingController::class, 'destroyExpense'])->name('expense.destroy');


// Категории дохода
Route::get('constants', [ConstantController::class, 'index'])->name('constants.index');
Route::post('constants/income', [ConstantController::class, 'storeIncome'])->name('constants.income.store');
Route::put('constants/income/{constant}', [ConstantController::class, 'updateIncome'])->name('constants.income.update');
Route::delete('constants/income/{constant}', [ConstantController::class, 'destroyIncome'])->name('constants.income.destroy');

// Категории расходов
Route::post('constants/expense', [ConstantController::class, 'storeExpense'])->name('constants.expense.store');
Route::put('constants/expense/{constant}', [ConstantController::class, 'updateExpense'])->name('constants.expense.update');
Route::delete('constants/expense/{constant}', [ConstantController::class, 'destroyExpense'])->name('constants.expense.destroy');

// СПОСОБЫ ОПЛАТЫ
Route::post('constants/payment', [ConstantController::class, 'storePayment'])->name('constants.payment.store');
Route::put('constants/payment/{constant}', [ConstantController::class, 'updatePayment'])->name('constants.payment.update');
Route::delete('constants/payment/{constant}', [ConstantController::class, 'destroyPayment'])->name('constants.payment.destroy');

// СТАТЬИ УЧЁТА
Route::post('constants/account-article', [ConstantController::class, 'storeAccountArticle'])->name('constants.account.store');
Route::put('constants/account-article/{constant}', [ConstantController::class, 'updateAccountArticle'])->name('constants.account.update');
Route::delete('constants/account-article/{constant}', [ConstantController::class, 'destroyAccountArticle'])->name('constants.account.destroy');

// КАТЕГОРИИ ПОСТАВОК
Route::post('constants/supply', [ConstantController::class, 'storeSupply'])->name('constants.supply.store');
Route::put('constants/supply/{constant}', [ConstantController::class, 'updateSupply'])->name('constants.supply.update');
Route::delete('constants/supply/{constant}', [ConstantController::class, 'destroySupply'])->name('constants.supply.destroy');

// ЕДИНИЦЫ ИЗМЕРЕНИЯ
Route::post('constants/unit', [ConstantController::class, 'storeUnit'])->name('constants.unit.store');
Route::put('constants/unit/{constant}', [ConstantController::class, 'updateUnit'])->name('constants.unit.update');
Route::delete('constants/unit/{constant}', [ConstantController::class, 'destroyUnit'])->name('constants.unit.destroy');

// УСЛОВИЯ ОПЛАТЫ
Route::post('constants/payment-term', [ConstantController::class, 'storePaymentTerm'])->name('constants.payment_term.store');
Route::put('constants/payment-term/{constant}', [ConstantController::class, 'updatePaymentTerm'])->name('constants.payment_term.update');
Route::delete('constants/payment-term/{constant}', [ConstantController::class, 'destroyPaymentTerm'])->name('constants.payment_term.destroy');

Route::resource('clients', ClientController::class);

Route::resource('suppliers', SupplierController::class);

Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');

Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');

Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');

Route::resource('warehouses', WarehouseController::class);


// Список поставок склада (карточка склада)
Route::get('/warehouses/{warehouse}/manage', [WarehouseController::class, 'manage'])->name('warehouses.manage');

// Форма добавления поставки
Route::get('/warehouses/{warehouse}/supplies/create', [WarehouseController::class, 'createSupply'])->name('warehouses.supplies.create');

// Сохранение новой поставки
Route::post('/warehouses/{warehouse}/supplies', [WarehouseController::class, 'storeSupply'])->name('warehouses.supplies.store');

// Редактирование поставки
Route::get('/warehouses/{warehouse}/supplies/{supply}/edit', [WarehouseController::class, 'editSupply'])->name('warehouses.supplies.edit');

// Обновление поставки
Route::put('/warehouses/{warehouse}/supplies/{supply}', [WarehouseController::class, 'updateSupply'])->name('warehouses.supplies.update');

// Удаление поставки
Route::delete('/warehouses/{warehouse}/supplies/{supply}', [WarehouseController::class, 'destroySupply'])->name('warehouses.supplies.destroy');



// ПРОИЗВОДСТВО



Route::resource('manufactured_products', ManufacturedProductController::class);

Route::patch('manufactured_products/{manufacturedProduct}/produce', [ManufacturedProductController::class, 'markAsProduced'])
    ->name('manufactured_products.produce');

Route::patch('manufactured_products/{product}/stock', [ManufacturedProductController::class, 'stock'])->name('manufactured_products.stock');


// МОДЕЛИ

Route::resource('product_models', ProductModelController::class);

// сырьё

Route::resource('raw-materials', RawMaterialController::class);

Route::get('/product-models/{id}/components', [ProductModelController::class, 'getComponents']);