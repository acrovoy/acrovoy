<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\ManagerProduct;

class PromocodeController extends Controller
{
    public function getPrice(Request $request)
    {
        $request->validate([
            'promocode' => 'required|string',
            'product_id' => 'required|integer',
        ]);

        $promocode = $request->input('promocode');
        $productId = $request->input('product_id');

        $manager = Manager::where('promocode', $promocode)
            ->first();

        if (!$manager) {
            return response()->json([
                'success' => false,
                'message' => 'Промокод не найден'
            ], 404);
        }

        $price = ManagerProduct::where('manager_id', $manager->id)
            ->where('product_id', $productId)
            ->value('price');

        if ($price === null) {
            return response()->json([
                'success' => false,
                'message' => 'Цена по промокоду не найдена'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'price' => $price,
        ]);
    }
}
