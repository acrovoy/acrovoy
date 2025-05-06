<?php

namespace App\Http\Controllers;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getCurrentVersion($app)
    {
        
        $current_version = Products::where('app', $app)
        ->first();
        
        return response()->json([
            'message' => 'Get version successfully',
            'data' => $current_version
        ], 200);

    }
}
