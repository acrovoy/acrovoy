<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\Products;
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
                'message' => 'Promocode has not been found'
            ], 404);
        }

        $price = ManagerProduct::where('manager_id', $manager->id)
            ->where('product_id', $productId)
            ->value('price');

        if ($price === null) {
            return response()->json([
                'success' => false,
                'message' => 'The price has not found for this promo'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'price' => $price,
        ]);
    }

    public function getOwnPrice(Request $request)
    {
        $request->validate([
            
            'product_id' => 'required|integer',
        ]);

        $product_id = $request->input('product_id');

        $price = Products::where('id', $product_id)
            ->value('discounted_price');
        $min_price = Products::where('id', $product_id)
            ->value('min_price');

        if ($price === null) {
            return response()->json([
                'success' => false,
                'message' => 'The price has not found for this promo'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'discounted_price' => $price,
            'min_price' => $min_price,
        ]);
    }
}
