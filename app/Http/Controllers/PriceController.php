<?php

namespace App\Http\Controllers;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'manager_id' => 'required|exists:managers,id',
            'price' => 'required|numeric|min:0',
        ]);

        $price = Price::updateOrCreate(
            [
                'product_id' => $validated['product_id'],
                'manager_id' => $validated['manager_id'],
            ],
            ['price' => $validated['price']]
        );

        return response()->json([
            'message' => 'Цена успешно сохранена',
            'price' => $price
        ], 201);
    }
}
