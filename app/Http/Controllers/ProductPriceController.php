<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductPrice;
use App\Models\ProductModel;
use App\Models\PriceType;
use App\Models\ManufacturedProduct;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProductPriceController extends Controller
{

    protected $connection = 'mysql_rattan';

    /**
     * Список всех цен
     */
    public function index()
{
    $prices = ProductPrice::with(['type'])->get();

    // Загружаем все ManufacturedProduct, чтобы сработал accessor cost
    $products = ManufacturedProduct::with('components.supply')->get()->keyBy('sku');

    foreach ($prices as $price) {
        $price->calculated_cost = $products[$price->sku]->cost ?? null;
    }

    return view('prices.index', compact('prices'));
}

    /**
     * Форма создания новой цены
     */
    public function create()
    {
        $models = ProductModel::all();
        $types = PriceType::all();
        return view('prices.create', compact('models', 'types'));
    }

    /**
     * Сохранение новой цены
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|exists:mysql_rattan.product_models,sku',
            'price_type_id' => 'required|exists:mysql_rattan.price_types,id',
            'price' => 'required|numeric|min:0',
        ]);

        ProductPrice::create($request->only(['sku', 'price_type_id', 'price']));

        return redirect()->route('product_prices.index')->with('success', 'Цена успешно добавлена!');
    }

    /**
     * Просмотр конкретной цены
     */
    public function show(ProductPrice $productPrice)
{
    $productPrice->load(['productModel', 'type']);

    // Получаем себестоимость через модель ManufacturedProduct
    $manufactured = \App\Models\ManufacturedProduct::where('sku', $productPrice->sku)->first();
    $productPrice->calculated_cost = $manufactured->cost ?? null;

    return view('prices.show', compact('productPrice'));
}

    /**
     * Форма редактирования цены
     */
    public function edit(ProductPrice $productPrice)
    {
        $models = ProductModel::all();
        $types = PriceType::all();
        return view('prices.edit', compact('productPrice', 'models', 'types'));
    }

    /**
     * Обновление цены
     */
    public function update(Request $request, ProductPrice $productPrice)
    {
        $request->validate([
            'sku' => 'required|exists:mysql_rattan.product_models,sku',
            'price_type_id' => 'required|exists:mysql_rattan.price_types,id',
            'price' => 'required|numeric|min:0',
        ]);

        $productPrice->update($request->only(['sku', 'price_type_id', 'price']));

        return redirect()->route('product_prices.index')->with('success', 'Цена успешно обновлена!');
    }

    /**
     * Удаление цены
     */
    public function destroy(ProductPrice $productPrice)
    {
        $productPrice->delete();
        return redirect()->route('product_prices.index')->with('success', 'Цена удалена!');
    }


    

public function exportExcel()
{
    $products = ProductModel::with(['prices'])->get();
    $priceTypes = PriceType::orderBy('name')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Заголовки
    $sheet->setCellValue('A1', 'SKU');
    $sheet->setCellValue('B1', 'Название');
    $sheet->setCellValue('C1', 'Себестоимость');

    $col = 'D';
    foreach ($priceTypes as $type) {
        $sheet->setCellValue($col.'1', $type->name);
        $col++;
    }

     // Загружаем все себестоимости сразу одним запросом
    $costs = ManufacturedProduct::get()->keyBy('sku');
    // Данные
    $row = 2;

    foreach ($products as $product) {
        $sheet->setCellValue('A'.$row, $product->sku);
        $sheet->setCellValue('B'.$row, $product->name);

        $cost = $costs[$product->sku]->cost ?? null; // <- используем геттер cost
        $sheet->setCellValue('C'.$row, $cost);

        $col = 'D';
        foreach ($priceTypes as $type) {
            $price = $product->prices->firstWhere('price_type_id', $type->id)->price ?? null;
            $sheet->setCellValue($col.$row, $price);
            $col++;
        }

        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    $filename = 'price_list.xlsx';
    $path = storage_path("app/$filename");

    $writer->save($path);

    return response()->download($path)->deleteFileAfterSend(true);
}

public function priceList(Request $request)
{
    $query = ProductModel::with('prices');

    // Поиск
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('sku', 'like', "%{$request->search}%")
              ->orWhere('name', 'like', "%{$request->search}%");
        });
    }

    // Фильтр по категории
    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    // Фильтр по наличию цен
    if ($request->has_price === '1') {
        $query->whereHas('prices');
    }
    if ($request->has_price === '0') {
        $query->whereDoesntHave('prices');
    }

    // Сортировка
    $sort = $request->sort ?? 'sku';
    $query->orderBy($sort);

    $products = $query->get();

    $priceTypes = PriceType::orderBy('id')->get();

    $costs = ManufacturedProduct::with('components.supply')->get()->keyBy('sku');

   

    return view('prices.price_list', compact('products', 'priceTypes', 'costs'));
}

}