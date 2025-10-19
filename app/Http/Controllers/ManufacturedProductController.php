<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManufacturedProduct;
use App\Models\ManufacturedProductComponent;
use App\Models\Supply;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\ManufacturedProductComponentCost;

class ManufacturedProductController extends Controller
{
    // Список изделий
    public function index()
    {
        $products = ManufacturedProduct::with('warehouse', 'components.supply', 'category')
        ->orderByDesc('created_at')
        ->get();



        return view('manufactured_products.index', compact('products'));
    }



  
    // Форма создания
    public function create()
{
    $warehouses = Warehouse::all();
    $supplies = Supply::all();
    $categories = Category::all(); // <-- добавлено
    return view('manufactured_products.create', compact('warehouses', 'supplies', 'categories'));
}

    // Сохранение нового изделия
    public function store(Request $request)
    {
        $product = ManufacturedProduct::create($request->only(['name','sku','category_id','warehouse_id','notes']));

        // Сохраняем состав, если передан
        if ($request->components) {
            foreach ($request->components as $component) {
                ManufacturedProductComponent::create([
                    'manufactured_product_id' => $product->id,
                    'supply_id' => $component['supply_id'],
                    'quantity' => $component['quantity']
                ]);
            }
        }

        return redirect()->route('manufactured_products.index')->with('success', 'Заказ на производство создан');
    }

    // Форма редактирования
    public function edit(ManufacturedProduct $manufacturedProduct)
{
    $warehouses = Warehouse::all();
    $supplies = Supply::all();
    $categories = Category::all(); // <-- добавлено
    $manufacturedProduct->load('components.supply');
    return view('manufactured_products.edit', compact('manufacturedProduct', 'warehouses', 'supplies', 'categories'));
}

    // Обновление изделия
    public function update(Request $request, ManufacturedProduct $manufacturedProduct)
{
    $oldStatus = $manufacturedProduct->status;
    $newStatus = $request->status;

    $manufacturedProduct->update($request->only(['name', 'sku', 'category_id', 'notes', 'status']));

    // Обновляем состав
    $manufacturedProduct->components()->delete();
    if ($request->components) {
        foreach ($request->components as $component) {
            ManufacturedProductComponent::create([
                'manufactured_product_id' => $manufacturedProduct->id,
                'supply_id' => $component['supply_id'],
                'quantity' => $component['quantity']
            ]);
        }
    }

    // Логика списания сырья при смене статуса
    if ($oldStatus !== 'produced' && $newStatus === 'produced') {
        foreach ($manufacturedProduct->components as $component) {
            $supply = $component->supply;
            if ($supply->quantity_remaining < $component->quantity) {
                return redirect()->back()->with('error', "Недостаточно сырья: {$supply->name}");
            }
            $supply->quantity_used += $component->quantity;
            $supply->quantity_remaining -= $component->quantity;
            $supply->save();
        }
    }

    // TODO: добавить логику для "stocked" (приход готовой продукции на склад)

    return redirect()->route('manufactured_products.index')->with('success', 'Изделие обновлено');
}

    // Удаление изделия
    public function destroy(ManufacturedProduct $manufacturedProduct)
    {
        $manufacturedProduct->components()->delete();
        $manufacturedProduct->delete();
        return redirect()->route('manufactured_products.index')->with('success', 'Изделие удалено');
    }



    public function markAsProduced(ManufacturedProduct $manufacturedProduct)
{
    if ($manufacturedProduct->status !== 'order') {
        return redirect()->back()->with('error', 'Изделие уже произведено или на складе.');
    }

    $totalCost = 0;

    foreach ($manufacturedProduct->components as $component) {
        $needed = $component->quantity;

        $supplies = \App\Models\Supply::where('sku', $component->supply->sku)
                    ->where('warehouse_id', $component->supply->warehouse_id)
                    ->where('quantity_remaining', '>', 0)
                    ->orderBy('date_received')
                    ->get();

        if ($supplies->sum('quantity_remaining') < $needed) {
            return redirect()->back()->with('error', "Недостаточно сырья: {$component->supply->name}");
        }

        foreach ($supplies as $supply) {
            if ($needed <= 0) break;

            $toUse = min($needed, $supply->quantity_remaining);

            $supply->quantity_used += $toUse;
            $supply->quantity_remaining -= $toUse;
            $supply->save();

            $unitCost = $supply->price_per_unit;
            $total = $toUse * $unitCost;

            // Создаём запись в таблице себестоимости компонентов
            ManufacturedProductComponentCost::create([
                'manufactured_product_id' => $manufacturedProduct->id,
                'manufactured_product_component_id' => $component->id,
                'supply_id' => $supply->id,
                'component_name' => $component->supply->name,
                'sku' => $component->supply->sku,
                'quantity' => $toUse,
                'unit' => $supply->unit,
                'unit_price' => $unitCost,
                'total_price' => $total,
            ]);

            $totalCost += $total;
            $needed -= $toUse;
        }
    }

    $manufacturedProduct->status = 'produced';
    $manufacturedProduct->cost = $totalCost;
    $manufacturedProduct->manufactured_at = now();

    // 🔢 Генерация серийного номера
    $date = now()->format('Ymd');
    $lastId = ManufacturedProduct::max('id') + 1;
    $serial = sprintf("SN-%s-%04d", $date, $lastId);

    $manufacturedProduct->serial_number = $serial;

    $manufacturedProduct->save();

    return redirect()->route('manufactured_products.index')
                     ->with('success', 'Изделие произведено и сырьё списано.');
}
public function stock(Request $request, ManufacturedProduct $product)
{
    if ($product->status !== 'produced') {
        return redirect()->back()->with('error', 'Изделие должно быть произведено перед отправкой на склад.');
    }

    $validated = $request->validate([
        'produced_quantity' => 'required|integer|min:1',
    ]);

    \DB::transaction(function() use ($product, $validated) {
        $product->produced_quantity = $validated['produced_quantity'];

        $supply = new \App\Models\Supply();
        $supply->warehouse_id = $product->warehouse_id;
        $supply->date_received = now();
        $supply->document_number = $product->serial_number;
        $supply->supplier_name = 'Markell Rattan';
        $supply->sku = $product->sku;
        $supply->name = $product->name;
        $supply->category_id = 2;
        $supply->unit = 'шт';
        $supply->quantity = $product->produced_quantity;
        $supply->price_per_unit = $product->cost;
        $supply->quantity_used = 0;
        $supply->quantity_remaining = $product->produced_quantity;
        $supply->notes = 'Готовая продукция от ' . now()->format('d.m.Y');
        $supply->save();

        $product->status = 'stocked';
        $product->save();
    });

    return redirect()->route('manufactured_products.index')->with('success', 'Изделие переведено на склад.');
}



}
