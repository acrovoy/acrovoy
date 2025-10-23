<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\ProductModelComponent;
use App\Models\Supply;
use Illuminate\Http\Request;
use App\Models\RawMaterial;

class ProductModelController extends Controller
{
    public function index()
    {
        $models = ProductModel::with('components.rawmaterial')->get();
        return view('product_models.index', compact('models'));
    }

    public function create()
    {
        $rawmaterials = RawMaterial::all();
        return view('product_models.create', compact('rawmaterials'));
    }

    public function store(Request $request)
    {

        
        $validated = $request->validate([
            'name' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('product_photos', 'public');
        }

        $model = ProductModel::create($validated);

        if ($request->components) {
            foreach ($request->components as $component) {
                ProductModelComponent::create([
                    'product_model_id' => $model->id,
                    'raw_material_id' => $component['raw_material_id'],
                    'quantity' => $component['quantity'],
                ]);
            }
        }

        return redirect()->route('product_models.index')->with('success', 'Модель создана');
    }

    public function edit(ProductModel $product_model)
    {
        $rawmaterials = RawMaterial::all();
        return view('product_models.edit', compact('product_model', 'rawmaterials'));
    }

    public function update(Request $request, ProductModel $product_model)
    {
        $validated = $request->validate([
            'name' => 'required',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'sku' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('product_photos', 'public');
        }

        $product_model->update($validated);

        // Обновляем компоненты
        $product_model->components()->delete();
        if ($request->components) {
            foreach ($request->components as $component) {
                ProductModelComponent::create([
                    'product_model_id' => $product_model->id,
                    'raw_material_id' => $component['raw_material_id'],
                    'quantity' => $component['quantity'],
                ]);
            }
        }

        return redirect()->route('product_models.index')->with('success', 'Модель обновлена');
    }


    public function getComponents($id)
{
    $model = ProductModel::with('components.rawmaterial')->findOrFail($id);

    $components = $model->components->map(function($comp) {
        // ищем supply с ненулевым остатком
        $supply = Supply::where('sku', $comp->rawmaterial->sku)
                        ->orWhere('name', $comp->rawmaterial->name)
                        ->where('quantity_remaining', '>', 0)
                        ->first();

        return [
            'name' => $comp->rawmaterial->name,
            'sku' => $comp->rawmaterial->sku,
            'quantity' => $comp->quantity,
            'supply_exists' => $supply ? true : false,
            'supply_id' => $supply ? $supply->id : null,
            'quantity_remaining' => $supply ? $supply->quantity_remaining : 0,
            'price_per_unit' => $supply ? $supply->price_per_unit : 0,
        ];
    });

    return response()->json($components);
}


    public function destroy(ProductModel $product_model)
    {
        $product_model->delete();
        return redirect()->route('product_models.index')->with('success', 'Модель удалена');
    }
}
