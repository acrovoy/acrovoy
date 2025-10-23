<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Models\Unit;

class RawMaterialController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::latest()->get();
        return view('raw_materials.index', compact('materials'));
    }

    public function create()
    {

        $units = Unit::orderBy('name')->get();
        return view('raw_materials.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('raw_material_photos', 'public');
        }

        RawMaterial::create($validated);

        return redirect()->route('raw-materials.index')->with('success', 'Сырьё добавлено');
    }

    public function edit(RawMaterial $raw_material)
    {

        $units = Unit::orderBy('name')->get();
        return view('raw_materials.edit', compact('raw_material', 'units'));
    }

    public function update(Request $request, RawMaterial $raw_material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('raw_material_photos', 'public');
        }

        $raw_material->update($validated);

        return redirect()->route('raw-materials.index')->with('success', 'Сырьё обновлено');
    }

    public function destroy(RawMaterial $raw_material)
    {
        $raw_material->delete();
        return redirect()->route('raw-materials.index')->with('success', 'Сырьё удалено');
    }
}