<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Supply;
use App\Models\Client;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Log; 
use App\Models\ProductSale;
use App\Models\ProductSaleItem;
use App\Models\PaymentMethod;
use App\Models\Income;


class ProductSalesController extends Controller
{
    protected $connection = 'mysql_rattan';

    // Список продаж
    public function index(Request $request)
{
    $query = ProductSale::with(['client', 'warehouse']);

    if ($request->filled('warehouse_id')) {
        $query->where('warehouse_id', $request->warehouse_id);
    }

    $sales = $query->get();

    // 🔥 Сумма всех продаж (с учетом фильтра)
    $totalSum = $sales->sum('total_amount');

    $warehouses = Warehouse::all();

    return view('product_sales.index', compact('sales', 'warehouses', 'totalSum'));
}

    // Форма создания новой продажи
    public function create(Request $request)
{
    $products = Supply::on('mysql_rattan')
    ->where('category_id', 2)
    ->where('quantity_remaining', '>', 0)
    ->select(
        DB::raw('MIN(id) as id'),           // нужен, чтобы передавать supply_id
        'sku',
        DB::raw('SUM(quantity_remaining) as total_quantity'),
        DB::raw('MIN(name) as name')
    )
    ->groupBy('sku')
    ->get();

    $clients = Client::on('mysql_rattan')->get();
    $warehouses = Warehouse::on('mysql_rattan')->get();
    $paymentMethods = PaymentMethod::all();

    $items = $request->has('add_item')
        ? array_merge($request->input('items', []), [['supply_id' => '', 'quantity' => 1, 'price' => 0]])
        : [['supply_id' => '', 'quantity' => 1, 'price' => 0]];

    $total_amount = collect($items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['price'] ?? 0));

    return view('product_sales.create', compact('products', 'clients', 'warehouses', 'items', 'total_amount','paymentMethods'));
}

public function edit(Request $request, $id)
{
    $sale = ProductSale::on('mysql_rattan')->findOrFail($id);
    $products = Supply::on('mysql_rattan')->get();
    $clients = Client::on('mysql_rattan')->get();
    $warehouses = Warehouse::on('mysql_rattan')->get();
    $paymentMethods = PaymentMethod::all();

    $items = $request->has('add_item')
        ? array_merge($sale->items->toArray(), [['supply_id' => '', 'quantity' => 1, 'price' => 0]])
        : $sale->items;

    $total_amount = collect($items)->sum(fn($i) => ($i['quantity'] ?? 0) * ($i['price'] ?? 0));

    return view('product_sales.edit', compact('sale', 'products', 'clients', 'warehouses', 'items', 'total_amount','paymentMethods'));
}

public function store(Request $request)
{
    if ($request->has('add_item')) {
        return $this->create($request);
    }

    $validated = $request->validate([
        'date' => 'required|date',
        'client_id' => 'nullable|exists:mysql_rattan.clients,id',
        'items' => 'required|array',
        'items.*.supply_id' => 'required|exists:mysql_rattan.supplies,id',
        'items.*.quantity' => 'required|numeric|min:1.00',
        'items.*.price' => 'required|numeric|min:0',
    ]);
// dd($request);
    DB::connection('mysql_rattan')->transaction(function () use ($request) {

        $sale = ProductSale::on('mysql_rattan')->create([
            'date' => $request->date,
            'client_id' => $request->client_id,
            'document_number' => $request->document_number,
            'total_amount' => $request->total_amount,
            'warehouse_id' => $request->warehouse_id,
            'payment_method_id' => $request->payment_method_id,
            'notes' => $request->notes,
            'status' => 'draft',
        ]);

        foreach ($request->items as $item) {

            // Получаем SKU выбранной позиции
            $sku = Supply::on('mysql_rattan')
                ->where('id', $item['supply_id'])
                ->value('sku');

            $qtyToRemove = $item['quantity'];

            // Получаем все поставки с этим SKU
            $supplies = Supply::on('mysql_rattan')
                ->where('sku', $sku)
                ->where('quantity_remaining', '>', 0)
                ->orderBy('id') // FIFO списание
                ->get();

            foreach ($supplies as $supply) {
                if ($qtyToRemove <= 0) break;

                // сколько можно взять из этой поставки
                $take = min($supply->quantity_remaining, $qtyToRemove);

                // уменьшаем остаток
                $supply->quantity_remaining -= $take;
                $supply->save();

                // записываем в ProductSaleItem
                ProductSaleItem::on('mysql_rattan')->create([
                    'product_sale_id' => $sale->id,
                    'supply_id' => $supply->id,
                    'sku' => $supply->sku,
                    'name' => $supply->name,
                    'quantity' => $take,
                    'price' => $item['price'],
                    'total' => $item['price'] * $take,
                ]);

                $qtyToRemove -= $take;
            }

            // Если не хватило товара — откат и ошибка
            if ($qtyToRemove > 0) {
                throw new \Exception("Недостаточно остатков для SKU: $sku");
            }
        }
    });

    return redirect()->route('product_sales.index')->with('success', 'Продажа успешно создана!');
}

public function update(Request $request, $id)
{
    if ($request->has('add_item')) {
        return $this->edit($request, $id);
    }

    $validated = $request->validate([
        'date' => 'required|date',
        'client_id' => 'nullable|exists:mysql_rattan.clients,id',
        'items' => 'required|array',
        'items.*.supply_id' => 'required|exists:mysql_rattan.supplies,id',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    DB::connection('mysql_rattan')->transaction(function () use ($request, $id) {
        $sale = ProductSale::on('mysql_rattan')->findOrFail($id);

        // вернуть остатки по старым позициям
        foreach ($sale->items as $old) {
            $supply = Supply::on('mysql_rattan')->find($old->supply_id);
            $supply->quantity_remaining += $old->quantity;
            $supply->save();
        }

        $sale->items()->delete();

        $sale->update([
            'date' => $request->date,
            'client_id' => $request->client_id,
            'document_number' => $request->document_number,
            'payment_method_id' => $request->payment_method_id,
            'total_amount' => $request->total_amount,
            'warehouse_id' => $request->warehouse_id,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $supply = Supply::on('mysql_rattan')->find($item['supply_id']);
            if ($supply->quantity_remaining < $item['quantity']) {
                throw new \Exception("Недостаточно продукта: {$supply->name}");
            }

            $supply->quantity_remaining -= $item['quantity'];
            $supply->save();

            ProductSaleItem::on('mysql_rattan')->create([
                'product_sale_id' => $sale->id,
                'supply_id' => $supply->id,
                'sku' => $supply->sku,
                'name' => $supply->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }
    });

    return redirect()->route('product_sales.index')->with('success', 'Продажа успешно обновлена!');
}




    // Просмотр продажи
    public function show($id)
{
    $productSale = ProductSale::on($this->connection)
        ->with(['client', 'warehouse', 'items'])
        ->findOrFail($id);

        


    return view('product_sales.show', compact('productSale'));
}

public function pay(ProductSale $productSale)
{
    DB::connection('mysql_rattan')->transaction(function () use ($productSale) {
        // Устанавливаем статус "paid"
        $productSale->status = 'paid';
        $productSale->save();

        // Берём название первого товара
        $firstItemName = $productSale->items->first()->name ?? 'Без названия';

        // Создаём запись в таблице incomes
        Income::create([
            'date' => $productSale->date,
            'document_number' => $productSale->document_number,
            'client_id' => $productSale->client_id,
            'description' => 'Продажа '.$firstItemName, 
            'category' => 'Продажа товара',
            'amount' => $productSale->total_amount,
            'payment_method' => $productSale->paymentMethod->name ?? null,
            'account_article' => 'Выручка',
            'warehouse' => $productSale->warehouse->name ?? null, // <-- название склада
            'comment' => null,
        ]);
    });

    return redirect()->back()->with('success', 'Продажа отмечена как оплаченная ✅');
}

public function draft(ProductSale $productSale)
{
    DB::connection('mysql_rattan')->transaction(function () use ($productSale) {
        // Возвращаем статус в draft
        $productSale->status = 'draft';
        $productSale->save();

        // Удаляем запись из incomes, если она существует
        Income::where('document_number', $productSale->document_number)
            ->where('category', 'Продажа товара')
            ->delete();
    });

    return back()->with('success', 'Статус продажи изменён на Черновик и запись в доходах удалена');
}

    // Удаление продажи
    public function destroy($id)
{
    // Используем транзакцию для надежности
    DB::connection($this->connection)->transaction(function () use ($id) {

        // Находим продажу вместе с ее элементами
        $sale = ProductSale::on($this->connection)->with('items')->find($id);

        // Если продажа не найдена, выбрасываем исключение, транзакция откатится
        if (!$sale) {
            throw new \Exception("Продажа с ID {$id} не найдена");
        }

        // Возвращаем количество товаров на склад
        foreach ($sale->items as $item) {
            $supply = Supply::on($this->connection)->find($item->supply_id);
            if ($supply) {
                $supply->quantity_remaining += $item->quantity;
                $supply->save();
            }
        }

        // Удаляем элементы продажи
        $sale->items()->delete();

        // Удаляем саму продажу
        $sale->delete();
    });

    return redirect()->route('product_sales.index')->with('success', 'Продажа успешно удалена!');
}

public function ship(ProductSale $productSale)
{
    if ($productSale->status !== 'paid') {
        return back()->with('error', 'Статус должен быть "paid" чтобы отправить.');
    }

    $productSale->status = 'shipped';
    $productSale->save();

    return back()->with('success', 'Продажа отмечена как отправленная.');
}

public function return(ProductSale $productSale)
{
    if ($productSale->status !== 'shipped') {
        return back()->with('error', 'Только отправленные продажи можно вернуть.');
    }

    $productSale->status = 'paid';
    $productSale->save();

    return back()->with('success', 'Статус возвращен на "paid".');
}

}
