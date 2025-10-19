<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Supply;
use App\Models\SupplyCategory;
use App\Models\Supplier;
use App\Models\Unit;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();

        if($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('location', 'like', "%{$request->search}%")
                  ->orWhere('manager', 'like', "%{$request->search}%");
        }

        $warehouses = $query->orderBy('name')->get();
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Склад добавлен!');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'manager' => 'nullable|string|max:255',
        ]);

        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Склад обновлён!');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Склад удалён!');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function manage(Warehouse $warehouse)
    {
        // Тут можно передавать товары или другую информацию для управления
        return view('warehouses.manage', compact('warehouse'));
    }



    // Форма добавления поставки
    public function createSupply(Warehouse $warehouse)
    {
        $categories = SupplyCategory::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('warehouses.supplies.create', compact('warehouse', 'categories', 'units', 'suppliers'));
    }

    // Сохранение новой поставки
    public function storeSupply(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'date_received' => 'required|date',
            'document_number' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:mysql_rattan.suppliers,id',
            'supplier_name' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:mysql_rattan.supply_categories,id',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'batch_number' => 'nullable|string|max:255',
            'quantity_used' => 'nullable|numeric|min:0',
            'quantity_remaining' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

         // Если quantity_remaining не задано, ставим равным quantity
        if (!isset($data['quantity_remaining'])) {
            $data['quantity_remaining'] = $data['quantity'];
        }

        $warehouse->supplies()->create($data);

        return redirect()->route('warehouses.manage', $warehouse)->with('success', 'Поставка добавлена!');
    }

    // Форма редактирования поставки
    public function editSupply(Warehouse $warehouse, Supply $supply)
    {
        $categories = SupplyCategory::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('warehouses.supplies.edit', compact('warehouse', 'supply', 'categories', 'units', 'suppliers'));
    }

    // Обновление поставки
    public function updateSupply(Request $request, Warehouse $warehouse, Supply $supply)
    {
        $data = $request->validate([
            'date_received' => 'required|date',
            'document_number' => 'nullable|string|max:255',
            'supplier_id' => 'nullable|exists:mysql_rattan.suppliers,id',
            'supplier_name' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:mysql_rattan.supply_categories,id',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'price_per_unit' => 'required|numeric|min:0',
            'batch_number' => 'nullable|string|max:255',
            'quantity_used' => 'nullable|numeric|min:0',
            'quantity_remaining' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $supply->update($data);

        return redirect()->route('warehouses.manage', $warehouse)->with('success', 'Поставка обновлена!');
    }

    // Удаление поставки
    public function destroySupply(Warehouse $warehouse, Supply $supply)
    {
        $supply->delete();

        return redirect()->route('warehouses.manage', $warehouse)->with('success', 'Поставка удалена!');
    }


}
