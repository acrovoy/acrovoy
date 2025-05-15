<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

       
       
        $products = Products::where('name', 'like', "%{$query}%")->get();

        return view('search.results', compact('query', 'products'));
    }
}
