<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Key;
use Illuminate\Http\JsonResponse;

class KeyController extends Controller
{
    public function index(): JsonResponse
    {
        // Возвращаем все ключи (в боевом проекте можно шифровать или фильтровать)
        return response()->json(Key::all());
    }


    public function getKeysByName(Request $request): JsonResponse
    {
        $name = $request->input('name');

        $keys = Key::where('name', $name)
        ->first();
        return response()->json($keys);
    }

}
